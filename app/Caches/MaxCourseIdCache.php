<?php

namespace App\Caches;

use App\Models\Course;

class MaxCourseIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_course_id';
    }

    public function getContent($id = null)
    {
        $course = Course::query()->latest('id')->first();

        return $course->id ?? 0;
    }

}
