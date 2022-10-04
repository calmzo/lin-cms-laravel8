<?php

namespace App\Services\Logic\Review;

use App\Events\ReviewAfterCreateEvent;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Review;
use App\Repositories\CourseRepository;
use App\Services\CourseStatService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;
use App\Services\Logic\Point\History\CourseReviewPointHistory;
use App\Traits\ReviewTrait;
use App\Services\Logic\LogicService;
use App\Traits\ClientTrait;
use App\Validators\CourseUserValidator;
use App\Validators\ReviewValidator;
use Illuminate\Support\Facades\DB;

class ReviewCreateService extends LogicService
{

    use ClientTrait;
    use CourseTrait;
    use ReviewTrait;

    public function handle($params)
    {

        DB::beginTransaction();
        try {
            $course = $this->checkCourse($params['course_id']);

            $uid = AccountLoginTokenService::userId();

            $validator = new CourseUserValidator();

            $courseUser = $validator->checkCourseUser($course->id, $uid);

            $validator->checkIfReviewed($course->id, $uid);

            $validator = new ReviewValidator();

            $data = [
                'client_type' => $this->getClientType(),
                'client_ip' => $this->getClientIp(),
                'course_id' => $course->id,
                'user_id' => $uid,
            ];

            $data['content'] = $params['content'];
            $data['rating1'] = $validator->checkRating($params['rating1']);
            $data['rating2'] = $validator->checkRating($params['rating2']);
            $data['rating3'] = $validator->checkRating($params['rating3']);
            $data['published'] = 1;

            $review = Review::query()->create($data);

            $this->updateCourseUserReview($courseUser);
            $this->recountCourseReviews($course);
            $this->updateCourseRating($course);
            $this->handleReviewPoint($review);

            ReviewAfterCreateEvent::dispatch($review);
            DB::commit();
            return $review;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
            DB::rollBack();
        }

    }

    protected function updateCourseUserReview(CourseUser $courseUser)
    {
        $courseUser->reviewed = 1;

        $courseUser->save();
    }

    protected function updateCourseRating(Course $course)
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

    protected function recountCourseReviews(Course $course)
    {
        $courseRepo = new CourseRepository();

        $reviewCount = $courseRepo->countReviews($course->id);

        $course->review_count = $reviewCount;

        $course->save();
    }

    protected function handleReviewPoint(Review $review)
    {
        $service = new CourseReviewPointHistory();

        $service->handle($review);
    }

}
