<?php

namespace App\Repositories;

use App\Models\AnswerLike;

class AnswerLikeRepository extends BaseRepository
{
    public function findByUserId($userId)
    {
        return AnswerLike::query()
            ->where('user_id', $userId)
            ->get();
    }

}
