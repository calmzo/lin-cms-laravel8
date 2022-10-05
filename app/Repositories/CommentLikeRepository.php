<?php

namespace App\Repositories;

use App\Models\CommentLike;

class CommentLikeRepository extends BaseRepository
{

    public function findByUserId($userId)
    {
        return CommentLike::query()
            ->where('user_id', $userId)
            ->get();
    }
}
