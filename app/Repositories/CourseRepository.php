<?php

namespace App\Repositories;

use App\Enums\CourseUserEnums;
use App\Enums\ReviewEnums;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Review;

class CourseRepository extends BaseRepository
{

    public function findById($id)
    {
        return Course::query()->find($id);
    }

    public function findByIds($ids, $columns = '*')
    {
        return Course::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }

    public function countCourses()
    {
        return Course::query()->where('published', 1)->count();
    }

    public function countReviews($courseId)
    {
        return Review::query()->where('course_id', $courseId)->where('published', ReviewEnums::PUBLISH_APPROVED)->count();
    }

    public function countLessons($courseId)
    {
        return Chapter::query()->where('course_id', $courseId)->where('parent_id', '>', 0)->count();

    }

    public function countUsers($courseId)
    {
        return CourseUser::query()->where('course_id', $courseId)->where('role_type', CourseUserEnums::ROLE_STUDENT)->count();
    }

    public function findLessons($courseId)
    {
        return Chapter::query()
            ->where('course_id', $courseId)
            ->where('parent_id', '>', 0)
            ->get();
    }

    public function findChapters($courseId)
    {
        return Chapter::query()
            ->where('course_id', $courseId)
            ->get();
    }

}
