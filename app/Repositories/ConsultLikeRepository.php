<?php

namespace App\Repositories;

use App\Models\ConsultLike;

class ConsultLikeRepository extends BaseRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findByUserId($userId)
    {
        return ConsultLike::query()
            ->where('user_id', $userId)
            ->get();
    }

}
