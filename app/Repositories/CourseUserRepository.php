<?php

namespace App\Repositories;

use App\Enums\CourseUserEnums;
use App\Models\CourseUser;

class CourseUserRepository extends BaseRepository
{
    public function findById($id)
    {
        return CourseUser::query()->find($id);
    }

    public function findCourseUser($courseId, $userId)
    {
        return CourseUser::withTrashed()->where('course_id', $courseId)->where('user_id', $userId)->first();
    }

    public function findCourseStudent($courseId, $userId)
    {
        $roleType = CourseUserEnums::ROLE_STUDENT;

        return $this->findRoleCourseUser($courseId, $userId, $roleType);
    }

    protected function findRoleCourseUser($courseId, $userId, $roleType)
    {
        return CourseUser::query()
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->where('role_type', $roleType)
            ->first();
    }

    public function findByTeacherIds($teacherIds)
    {
        $roleType = CourseUserEnums::ROLE_TEACHER;
        return CourseUser::query()
            ->whereIn('user_id', $teacherIds)
            ->where('role_type', $roleType)
            ->get();
    }

    public function findPlanCourseUser($courseId, $userId, $planId)
    {
        return CourseUser::query()
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->where('plan_id', $planId)
            ->first();
    }

}
