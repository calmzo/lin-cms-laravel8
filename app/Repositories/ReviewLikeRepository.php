<?php

namespace App\Repositories;

use App\Models\ReviewLike;

class ReviewLikeRepository extends BaseRepository
{

    public function findReviewLike($reviewId, $userId)
    {
        return ReviewLike::withTrashed()->where('review_id', $reviewId)->where('user_id', $userId)->first();
    }

    public function findByUserId($userId)
    {
        return ReviewLike::query()
            ->where('user_id', $userId)
            ->get();
    }
}
