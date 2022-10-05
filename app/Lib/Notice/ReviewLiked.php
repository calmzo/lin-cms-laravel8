<?php

namespace App\Lib\Notice;


use App\Enums\NotificationEnums;
use App\Models\Notification;
use App\Models\Review;
use App\Models\User;
use App\Repositories\CourseRepository;

class ReviewLiked
{

    public function handle(Review $review, User $sender)
    {
        $reviewContent = kg_substr($review->content, 0, 36);

        $course = $this->findCourse($review->course_id);

        $notification = new Notification();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $review->user_id;
        $notification->event_id = $review->id;
        $notification->event_type = NotificationEnums::TYPE_REVIEW_LIKED;
        $notification->event_info = [
            'course' => ['id' => $course->id, 'title' => $course->title],
            'review' => ['id' => $review->id, 'content' => $reviewContent],
        ];

        $notification->save();
    }

    protected function findCourse($id)
    {
        $courseRepo = new CourseRepository();

        return $courseRepo->findById($id);
    }

}
