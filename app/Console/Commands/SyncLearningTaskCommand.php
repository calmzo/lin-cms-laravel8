<?php

namespace App\Console\Commands;

use App\Enums\CourseEnums;
use App\Models\ChapterUser;
use App\Models\Learning;
use App\Repositories\ChapterRepository;
use App\Repositories\ChapterUserRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CourseUserRepository;
use App\Repositories\LearningRepository;
use App\Services\Sync\LearningSyncService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncLearningTaskCommand extends Command
{
    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_learning_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $redis = $this->getRedis();

        $sync = new LearningSyncService();

        $syncKey = $sync->getSyncKey();

        $requestIds = $redis->sMembers($syncKey);

        if (!$requestIds) return;

        foreach ($requestIds as $requestId) {

            $itemKey = $sync->getItemKey($requestId);

            $this->handleLearning($itemKey);

            $redis->sRem($syncKey, $requestId);
        }
    }

    /**
     * @param string $itemKey
     */
    protected function handleLearning($itemKey)
    {
        /**
         * @var Learning $cacheLearning
         */
        $cacheLearning = $this->getCache()->get($itemKey);

        if (!$cacheLearning) return;

        $learningRepo = new LearningRepository();

        $dbLearning = $learningRepo->findByRequestId($cacheLearning->request_id);

        if (!$dbLearning) {

            $cacheLearning->create();

            $this->updateChapterUser($cacheLearning);

        } else {

            $dbLearning->duration += $cacheLearning->duration;
            $dbLearning->position = $cacheLearning->position;
            $dbLearning->active_time = $cacheLearning->active_time;

            $dbLearning->update();

            $this->updateChapterUser($dbLearning);
        }

        $this->cache->delete($itemKey);
    }

    /**
     * @param Learning $learning
     */
    protected function updateChapterUser(Learning $learning)
    {
        $chapterUserRepo = new ChapterUserRepository();

        $chapterUser = $chapterUserRepo->findPlanChapterUser($learning->chapter_id, $learning->user_id, $learning->plan_id);

        if (!$chapterUser) return;

        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findById($learning->chapter_id);

        if (!$chapter) return;

        $chapterUser->duration += $learning->duration;

        /**
         * 消费规则
         *
         * 1.点播观看时间大于时长30%
         * 2.直播观看时间超过10分钟
         * 3.图文浏览即消费
         */
        if ($chapter->model == CourseEnums::MODEL_VOD) {

            $duration = $chapter->attrs['duration'] ?: 300;

            $progress = floor(100 * $chapterUser->duration / $duration);

            $chapterUser->position = floor($learning->position);
            $chapterUser->progress = $progress < 100 ? $progress : 100;
            $chapterUser->consumed = $chapterUser->duration > 0.3 * $duration ? 1 : 0;

        } elseif ($chapter->model == CourseEnums::MODEL_LIVE) {

            $chapterUser->consumed = $chapterUser->duration > 600 ? 1 : 0;

        } elseif ($chapter->model == CourseEnums::MODEL_READ) {

            $chapterUser->consumed = 1;
        }

        $chapterUser->update();

        if ($chapterUser->consumed == 1) {

            $this->updateCourseUser($learning);

            $this->handleStudyPoint($chapterUser);
        }
    }

    /**
     * @param Learning $learning
     */
    protected function updateCourseUser(Learning $learning)
    {
        $courseUserRepo = new CourseUserRepository();

        $courseUser = $courseUserRepo->findPlanCourseUser($learning->course_id, $learning->user_id, $learning->plan_id);

        if (!$courseUser) return;

        $courseRepo = new CourseRepository();

        $courseLessons = $courseRepo->findLessons($learning->course_id);

        if ($courseLessons->count() == 0) return;

        $userLearnings = $courseRepo->findUserLearnings($learning->course_id, $learning->user_id, $learning->plan_id);

        if ($userLearnings->count() == 0) return;

        $consumedUserLearnings = [];

        foreach ($userLearnings->toArray() as $userLearning) {
            if ($userLearning['consumed'] == 1) {
                $consumedUserLearnings[] = $userLearning;
            }
        }

        if (count($consumedUserLearnings) == 0) return;

        $duration = 0;

        foreach ($consumedUserLearnings as $userLearning) {
            $duration += $userLearning['duration'];
        }

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $consumedUserLessonIds = kg_array_column($consumedUserLearnings, 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $progress = intval(100 * $consumedCount / $totalCount);

        $courseUser->progress = $progress;
        $courseUser->duration = $duration;
        $courseUser->update();
    }

    /**
     * @param ChapterUser $chapterUser
     */
    protected function handleStudyPoint(ChapterUser $chapterUser)
    {
        $service = new ChapterStudyPointHistory();

        $service->handle($chapterUser);
    }

}
