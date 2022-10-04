<?php

namespace App\Services\Logic\Point\History;


use App\Enums\PointHistoryEnums;
use App\Models\Review;
use App\Repositories\CourseRepository;
use App\Repositories\PointHistoryRepository;
use App\Repositories\UserRepository;
use App\Models\PointHistory;
use App\Services\Logic\Point\PointHistoryService;

class CourseReviewPointHistory extends PointHistoryService
{

    public function handle(Review $review)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'] ?? [];

        $eventEnabled = $eventRule['course_review']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['course_review']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $review->id;

        $eventType = PointHistoryEnums::EVENT_COURSE_REVIEW;

        $historyRepo = new PointHistoryRepository();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepository();

        $user = $userRepo->findById($review->user_id);

        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($review->course_id);

        $eventInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
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
