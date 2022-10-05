<?php

namespace App\Builders;

use App\Repositories\ChapterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;

class LiveListBuilder extends BaseBuilder
{

    public function handleCourses(array $lives)
    {
        $courses = $this->getCourses($lives);

        foreach ($lives as $key => $live) {
            $lives[$key]['course'] = $courses[$live['course_id']] ?? new \stdClass();
        }

        return $lives;
    }

    public function handleChapters(array $lives)
    {
        $chapters = $this->getChapters($lives);

        foreach ($lives as $key => $live) {
            $lives[$key]['chapter'] = $chapters[$live['chapter_id']] ?? new \stdClass();
        }

        return $lives;
    }

    public function getCourses(array $lives)
    {
        $courseIds = array_column_unique($lives, 'course_id');

        $courseRepo = new CourseRepository();

        $courses = $courseRepo->findByIds($courseIds, ['id', 'title', 'cover', 'teacher_id']);

        $teacherIds = $courses->pluck('teacher_id')->toArray();
        $userRepo = new UserRepository();

        $users = $userRepo->findShallowUserByIds($teacherIds);

//        $baseUrl = kg_cos_url();

        $teachers = [];

        foreach ($users->toArray() as $user) {
//            $user['avatar'] = $baseUrl . $user['avatar'];
            $teachers[$user['id']] = $user;
        }

        $result = [];

        foreach ($courses->toArray() as $course) {
//            $course['cover'] = $baseUrl . $course['cover'];
            $course['teacher'] = $teachers[$course['teacher_id']] ?? new \stdClass();
            $result[$course['id']] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'teacher' => $course['teacher'],
            ];
        }

        return $result;
    }

    public function getChapters(array $lives)
    {
        $ids = array_column_unique($lives, 'chapter_id');

        $chapterRepo = new ChapterRepository();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title']);
        $result = $chapters->pluck('id')->toArray();
        return $result;
    }

}
