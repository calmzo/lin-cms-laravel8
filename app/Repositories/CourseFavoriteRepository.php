<?php

namespace App\Repositories;

use App\Models\CourseFavorite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseFavoriteRepository extends BaseRepository
{
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15): LengthAwarePaginator
    {
        $query = CourseFavorite::query();

        if (!empty($where['course_id'])) {
            $query->where('course_id', $where['course_id']);
        }

        if (!empty($where['user_id'])) {
            $query->where('user_id', $where['user_id']);
        }

        switch ($sort) {
            default:
                $query->orderByDesc('id');
                break;
        }


        return $query->paginate($limit, ['*'], 'page', $page);
    }

}
