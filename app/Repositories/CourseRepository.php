<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository extends BaseRepository
{
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

}
