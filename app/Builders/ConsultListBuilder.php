<?php

namespace App\Builders;

use App\Repositories\ChapterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;

class ConsultListBuilder
{

    public function handleCourses(array $consults)
    {
        $courses = $this->getCourses($consults);

        foreach ($consults as $key => $consult) {
            $consults[$key]['course'] = $courses[$consult['course_id']] ?? new \stdClass();
        }

        return $consults;
    }

    public function handleUsers(array $consults)
    {
        $users = $this->getUsers($consults);

        foreach ($consults as $key => $consult) {
            $consults[$key]['owner'] = $users[$consult['owner_id']] ?? new \stdClass();
            $consults[$key]['replier'] = $users[$consult['replier_id']] ?? new \stdClass();
        }

        return $consults;
    }

    public function getCourses(array $consults)
    {
        $ids = array_column_unique($consults, 'course_id');

        $courseRepo = new CourseRepository();

        $courses = $courseRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getChapters(array $consults)
    {
        $ids = array_column_unique($consults, 'chapter_id');

        $chapterRepo = new ChapterRepository();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($chapters->toArray() as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

    public function getUsers(array $consults)
    {
        $ownerIds = array_column_unique($consults, 'user_id');
        $replierIds = array_column_unique($consults, 'replier_id');
        $ids = array_merge($ownerIds, $replierIds);

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
