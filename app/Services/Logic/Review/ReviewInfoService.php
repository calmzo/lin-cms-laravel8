<?php

namespace App\Services\Logic\Review;

use App\Models\Review;
use App\Models\User;
use App\Repositories\CourseRepository;
use App\Repositories\ReviewLikeRepository;
use App\Repositories\UserRepository;
use App\Traits\ReviewTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserTrait;

class ReviewInfoService extends LogicService
{

    use ReviewTrait;
    use UserTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        return $this->handleReview($review, $user);
    }

    protected function handleReview(Review $review, User $user)
    {
        $course = $this->handleCourseInfo($review->course_id);
        $owner = $this->handleShallowUserInfo($review->owner_id);
        $me = $this->handleMeInfo($review, $user);

        return [
            'id' => $review->id,
            'content' => $review->content,
            'reply' => $review->reply,
            'rating' => $review->rating,
            'rating1' => $review->rating1,
            'rating2' => $review->rating2,
            'rating3' => $review->rating3,
            'published' => $review->published,
            'deleted' => $review->deleted,
            'like_count' => $review->like_count,
            'create_time' => $review->create_time,
            'update_time' => $review->update_time,
            'course' => $course,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleCourseInfo($courseId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseId);

        if (!$course) return new \stdClass();

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function handleMeInfo(Review $review, User $user)
    {
        $me = [
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $review->user_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new ReviewLikeRepository();

            $like = $likeRepo->findReviewLike($review->id, $user->id);

            if ($like) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
