<?php

namespace App\Services\Logic\Review;

use App\Models\Course;
use App\Services\CourseStatService;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;
use App\Traits\ReviewTrait;
use App\Validators\ReviewValidator;
use Illuminate\Support\Facades\DB;

class ReviewUpdateService extends LogicService
{

    use CourseTrait;
    use ReviewTrait;

    public function handle($id, $params)
    {

        $review = $this->checkReview($id);

        $course = $this->checkCourse($review->course_id);

        $uid = AccountLoginTokenService::userId();

        $validator = new ReviewValidator();

        $validator->checkOwner($uid, $review->user_id);

        $validator->checkIfAllowEdit($review);


        DB::beginTransaction();
        try {
            $data = [];

            $data['content'] = $params['content'] ?? '';
            $data['rating1'] = $validator->checkRating($params['rating1']);
            $data['rating2'] = $validator->checkRating($params['rating2']);
            $data['rating3'] = $validator->checkRating($params['rating3']);

            $review->update($data);

            $this->updateCourseRating($course);

            DB::commit();
            return $review;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
            DB::rollBack();
        }

    }

    protected function updateCourseRating(Course $course)
    {
        $service = new CourseStatService();
        $service->updateRating($course->id);
    }

}
