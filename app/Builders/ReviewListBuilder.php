<?php

namespace App\Builders;

use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;

class ReviewListBuilder
{

    public function handleCourses(array $reviews)
    {
        $courses = $this->getCourses($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['course'] = $courses[$review['course_id']] ?? new \stdClass();
        }

        return $reviews;
    }

    public function handleUsers(array $reviews)
    {
        $users = $this->getUsers($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['owner'] = $users[$review['owner_id']] ?? new \stdClass();
        }

        return $reviews;
    }

    public function getCourses(array $reviews)
    {
        $ids = array_column_unique($reviews, 'course_id');

        $courseRepo = new CourseRepository();

        $courses = $courseRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getUsers(array $reviews)
    {
        $ids = array_column_unique($reviews, 'user_id');

        $userRepo = new UserRepository();

        $users = $userRepo->findShallowUserByIds($ids);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
//            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
