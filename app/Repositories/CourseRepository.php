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

}
