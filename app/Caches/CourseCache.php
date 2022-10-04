<?php

namespace App\Caches;


use App\Repositories\CourseRepository;

class CourseCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($id);

        return $course ?: null;
    }

}
