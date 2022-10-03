<?php

namespace App\Builders;

use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;

class CourseFavoriteListBuilder
{

    public function handleCourses(array $relations)
    {
        $courses = $this->getCourses($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['course'] = $courses[$value['course_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getCourses(array $relations)
    {
        $ids = array_column_unique($relations, 'course_id');

        $courseRepo = new CourseRepository();

        $columns = [
            'id', 'title', 'cover',
            'market_price', 'vip_price',
            'rating', 'model', 'level', 'attrs',
            'user_count', 'lesson_count', 'review_count', 'favorite_count',
        ];

        $courses = $courseRepo->findByIds($ids, $columns);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($courses->toArray() as $course) {
//            $course['cover'] = $baseUrl . $course['cover'];
            $course['market_price'] = (float)$course['market_price'];
            $course['vip_price'] = (float)$course['vip_price'];
            $course['rating'] = (float)$course['rating'];
            $course['attrs'] = json_decode($course['attrs'], true);
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getUsers(array $relations)
    {
        $ids = array_column_unique($relations, 'user_id');

        $userRepo = new UserRepository();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
//            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
