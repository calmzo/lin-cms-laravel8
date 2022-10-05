<?php

namespace App\Caches;

use App\Enums\UserEnums;
use App\Models\User;

class IndexTeacherListCache extends Cache
{

    protected $lifetime = 1 * 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_teacher_list';
    }

    public function getContent($id = null)
    {
        $teachers = $this->findTeachers();

        if ($teachers->count() == 0) return [];

        $result = [];

//        $baseUrl = kg_cos_url();

        foreach ($teachers->toArray() as $teacher) {

//            $teacher['avatar'] = $baseUrl . $teacher['avatar'];

            $result[] = [
                'id' => $teacher['id'],
                'name' => $teacher['name'],
                'title' => $teacher['title'],
                'avatar' => $teacher['avatar'],
                'about' => $teacher['about'],
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findTeachers($limit = 8)
    {
        return User::query()
            ->inRandomOrder()
            ->where('edu_role', UserEnums::EDU_ROLE_TEACHER)
            ->limit($limit)
            ->get();
    }

}
