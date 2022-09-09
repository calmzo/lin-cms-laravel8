<?php

namespace App\Lib\Search;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;

class CourseDocument
{

    /**
     * 设置文档
     *
     * @param Course $course
     * @return \XSDocument
     */
    public function setDocument(Course $course)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($course);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param Course $course
     * @return array
     */
    public function formatDocument(Course $course)
    {
        if (is_array($course->attrs) || is_object($course->attrs)) {
            $course->attrs = json_encode($course->attrs);
        }

        if (is_array($course->tags) || is_object($course->tags)) {
            $course->tags = json_encode($course->tags);
        }

        $teacher = '{}';

        if ($course->teacher_id > 0) {
            $teacher = $this->handleUser($course->teacher_id);
        }

        $category = '{}';

        if ($course->category_id > 0) {
            $category = $this->handleCategory($course->category_id);
        }

        $userCount = $course->user_count;

        if ($course->fake_user_count > $course->user_count) {
            $userCount = $course->fake_user_count;
        }

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'keywords' => $course->keywords,
            'category_id' => $course->category_id,
            'teacher_id' => $course->teacher_id,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'rating' => $course->rating,
            'score' => $course->score,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'tags' => $course->tags,
            'category' => $category,
            'teacher' => $teacher,
            'user_count' => $userCount,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

    protected function handleUser($id)
    {
        $user = User::query()->find($id);
        return json_encode([
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ]);
    }

    protected function handleCategory($id)
    {
        $category = Category::query()->find($id);

        return json_encode([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

}
