<?php

namespace App\Services\Logic\Deliver;

use App\Enums\CourseEnums;
use App\Enums\CourseUserEnums;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\User;
use App\Services\Logic\LogicService;

class CourseDeliverService extends LogicService
{

    public function handle(Course $course, User $user)
    {
        $this->revokeCourseUser($course, $user);
        $this->handleCourseUser($course, $user);
    }

    protected function handleCourseUser(Course $course, User $user)
    {
        if ($course->model == CourseEnums::MODEL_OFFLINE) {
            $expiryTime = strtotime($course->attrs['end_date']);
        } else {
            $expiryTime = strtotime("+{$course->study_expiry} months");
        }

        $courseUser = new CourseUser();
        $courseUser->user_id = $user->id;
        $courseUser->course_id = $course->id;
        $courseUser->expiry_time = $expiryTime;
        $courseUser->role_type = CourseUserEnums::ROLE_STUDENT;
        $courseUser->source_type = CourseUserEnums::SOURCE_CHARGE;
        $courseUser->save();

        $course->user_count += 1;
        $course->save();

        $user->course_count += 1;
        $user->save();
    }

    protected function revokeCourseUser(Course $course, User $user)
    {
        CourseUser::query()->where('course_id', $course->id)->where('user_id', $user->id)->delete();
    }

}
