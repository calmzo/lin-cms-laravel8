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

    public function findCommentLike($commentId, $userId)
    {
        return CommentLike::withTrashed()->where('comment_id', $commentId)->where('user_id', $userId)->first();
    }
}
