<?php

namespace App\Repositories;

use App\Models\CourseRating;
use App\Models\Review;

class CourseRatingRepository extends BaseRepository
{
    /**
     * @param $courseId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findByCourseId($courseId)
    {
        return CourseRating::query()->where('course_id', $courseId)->first();
    }

    public function averageRating($courseId)
    {
        return Review::query()->where('course_id', $courseId)->where('published', 1)->average('rating');

    }

    public function averageRating1($courseId)
    {
        return (float)Review::query()->where('course_id', $courseId)->where('published', 1)->average('rating1');
    }

    public function averageRating2($courseId)
    {
        return (float)Review::query()->where('course_id', $courseId)->where('published', 1)->average('rating2');
    }

    public function averageRating3($courseId)
    {
        return (float)Review::query()->where('course_id', $courseId)->where('published', 1)->average('rating3');
    }

}
