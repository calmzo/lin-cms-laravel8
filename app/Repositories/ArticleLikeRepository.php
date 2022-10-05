<?php

namespace App\Repositories;

use App\Models\ArticleLike;

class ArticleLikeRepository extends BaseRepository
{

    public function findArticleLike($articleId, $uid)
    {
        return ArticleLike::withTrashed()->where('article_id', $articleId)->where('user_id', $uid)->first();
    }
}
