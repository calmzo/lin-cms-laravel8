<?php

namespace App\Caches;

use App\Models\User;
use App\Repositories\CourseRepository;

class CourseTeacherListCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_teacher_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepository();

        $users = $courseRepo->findTeachers($id);

        if ($users->count() == 0) {
            return [];
        }

        return $this->handleContent($users);
    }

    /**
     * @param User[] $users
     * @return array
     */
    public function handleContent($users)
    {
        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'vip' => $user->vip,
                'title' => $user->title,
                'about' => $user->about,
            ];
        }

        return $result;
    }

}
