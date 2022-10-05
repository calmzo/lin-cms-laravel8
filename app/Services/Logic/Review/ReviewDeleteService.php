<?php

namespace App\Services\Logic\Review;

use App\Events\ReviewAfterDeleteEvent;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Services\CourseStatService;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;
use App\Traits\ReviewTrait;
use App\Validators\ReviewValidator;

class ReviewDeleteService extends LogicService
{

    use CourseTrait;
    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $course = $this->checkCourse($review->course_id);

        $uid = AccountLoginTokenService::userId();

        $validator = new ReviewValidator();

        $validator->checkOwner($uid, $review->user_id);

        $review->delete();

        $this->recountCourseReviews($course);
        $this->updateCourseRating($course);
        ReviewAfterDeleteEvent::dispatch($review);
    }

    protected function recountCourseReviews(Course $course)
    {
        $courseRepo = new CourseRepository();

        $reviewCount = $courseRepo->countReviews($course->id);

        $course->review_count = $reviewCount;

        $course->save();
    }

    protected function updateCourseRating(Course $course)
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

}
