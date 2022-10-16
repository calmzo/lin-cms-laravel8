<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\ChapterUser;
use App\Models\PointHistory;
use App\Repositories\ChapterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\PointHistoryRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\Point\PointHistoryService;

class ChapterStudyService extends PointHistoryService
{

    public function handle(ChapterUser $chapterUser)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['chapter_study']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['chapter_study']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $chapterUser->id;

        $eventType = PointHistoryEnums::EVENT_CHAPTER_STUDY;

        $historyRepo = new PointHistoryRepository();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepository();

        $user = $userRepo->findById($chapterUser->user_id);

        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($chapterUser->course_id);

        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findById($chapterUser->chapter_id);

        $eventInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
            'chapter' => [
                'id' => $chapter->id,
                'title' => $chapter->title,
            ]
        ];

        $history = new PointHistory();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_info = $eventInfo;
        $history->event_point = $eventPoint;

        $this->handlePointHistory($history);
    }

}
