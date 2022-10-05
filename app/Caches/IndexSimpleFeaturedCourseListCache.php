<?php

namespace App\Caches;

use App\Models\Course;

/**
 * 简版推荐课程
 */
class IndexSimpleFeaturedCourseListCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_simple_featured_course_list';
    }

    public function getContent($id = null)
    {
        $limit = 8;

        $courses = $this->findCourses($limit);

        if ($courses->count() == 0) {
            return [];
        }

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

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findCourses($limit = 8)
    {
        return Course::query()
            ->where('featured', 1)
            ->where('published', 1)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

}
