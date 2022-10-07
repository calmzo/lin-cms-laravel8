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

    public function findAnswerLike($answerId, $userId)
    {
        return AnswerLike::withTrashed()->where('answer_id', $answerId)->where('user_id', $userId)->first();
    }


}
