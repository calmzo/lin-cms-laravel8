<?php

namespace App\Caches;

use App\Models\Course;
use App\Repositories\PackageRepository;

class PackageCourseListCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "package_course_list:{$id}";
    }

    public function getContent($id = null)
    {
        $packageRepo = new PackageRepository();

        $courses = $packageRepo->findCourses($id);

        if ($courses->count() == 0) {
            return [];
        }

        return $this->handleContent($courses);
    }

    /**
     * @param Course[] $courses
     * @return array
     */
    public function handleContent($courses)
    {
        $result = [];

        foreach ($courses as $course) {

            $userCount = $course->user_count;

            if ($course->fake_user_count > $course->user_count) {
                $userCount = $course->fake_user_count;
            }

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'model' => $course->model,
                'level' => $course->level,
                'rating' => round($course->rating, 1),
                'market_price' => (float)$course->market_price,
                'vip_price' => (float)$course->vip_price,
                'user_count' => $userCount,
                'lesson_count' => $course->lesson_count,
                'review_count' => $course->review_count,
                'favorite_count' => $course->favorite_count,
            ];
        }

        return $result;
    }

}
