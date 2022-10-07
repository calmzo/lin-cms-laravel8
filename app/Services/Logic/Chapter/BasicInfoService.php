<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Chapter;

use App\Enums\ChapterLiveEnum;
use App\Enums\CourseEnums;
use App\Models\Chapter;
use App\Models\Course;
use App\Repositories\ChapterRepository;
use App\Services\ChapterVodService;
use App\Lib\LiveService;
use App\Services\Logic\LogicService;
use App\Traits\ChapterTrait;
use App\Traits\CourseTrait;

class BasicInfoService extends LogicService
{

    use CourseTrait;
    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $course = $this->checkCourse($chapter->course_id);

        $result = $this->handleBasicInfo($chapter);

        $result['course'] = $this->handleCourseInfo($course);

        return $result;
    }

    public function handleBasicInfo(Chapter $chapter)
    {
        $result = [];

        switch ($chapter->model) {
            case CourseEnums::MODEL_VOD:
                $result = $this->formatChapterVod($chapter);
                break;
            case CourseEnums::MODEL_LIVE:
                $result = $this->formatChapterLive($chapter);
                break;
            case CourseEnums::MODEL_READ:
                $result = $this->formatChapterRead($chapter);
                break;
        }

        return $result;
    }

    public function handleCourseInfo(Course $course)
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function formatChapterVod(Chapter $chapter)
    {
        $chapterVodService = new ChapterVodService();

        $playUrls = $chapterVodService->getPlayUrls($chapter->id);

        /**
         *过滤播放地址为空的条目
         */
        foreach ($playUrls as $key => $value) {
            if (empty($value['url'])) unset($playUrls[$key]);
        }

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'published' => $chapter->published,
            'deleted' => $chapter->deleted,
            'play_urls' => $playUrls,
            'resource_count' => $chapter->resource_count,
            'comment_count' => $chapter->comment_count,
            'consult_count' => $chapter->consult_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
        ];
    }

    protected function formatChapterLive(Chapter $chapter)
    {
        $liveService = new LiveService();

        $streamName = ChapterLiveEnum::generateStreamName($chapter->id);

        $playUrls = $liveService->getPullUrls($streamName);

        $chapterRepo = new ChapterRepository();

        $live = $chapterRepo->findChapterLive($chapter->id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'published' => $chapter->published,
            'deleted' => $chapter->deleted,
            'play_urls' => $playUrls,
            'start_time' => $live->start_time,
            'end_time' => $live->end_time,
            'status' => $live->status,
            'resource_count' => $chapter->resource_count,
            'comment_count' => $chapter->comment_count,
            'consult_count' => $chapter->consult_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
        ];
    }

    protected function formatChapterRead(Chapter$chapter)
    {
        $chapterRepo = new ChapterRepository();

        $read = $chapterRepo->findChapterRead($chapter->id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'content' => $read->content,
            'published' => $chapter->published,
            'deleted' => $chapter->deleted,
            'resource_count' => $chapter->resource_count,
            'comment_count' => $chapter->comment_count,
            'consult_count' => $chapter->consult_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
        ];
    }

}
