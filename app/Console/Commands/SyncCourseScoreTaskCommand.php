<?php

namespace App\Console\Commands;

use App\Repositories\CourseRepository;
use App\Services\Sync\CourseScoreSyncService;
use App\Services\Utils\CourseScoreService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncCourseScoreTaskCommand extends Command
{
    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_course_score_task';

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

        $key = $this->getSyncKey();

        $courseIds = $redis->srandmember($key, 1000);

        if (!$courseIds) return;

        $courseRepo = new CourseRepository();

        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) return;

        $service = new CourseScoreService();

        foreach ($courses as $course) {
            $service->handle($course);
        }

        $redis->srem($key, ...$courseIds);
    }

    protected function getSyncKey()
    {
        $sync = new CourseScoreSyncService();

        return $sync->getSyncKey();
    }
}
