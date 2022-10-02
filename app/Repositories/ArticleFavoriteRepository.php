<?php

namespace App\Repositories;

use App\Models\ArticleFavorite;

class ArticleFavoriteRepository extends BaseRepository
{
    public function findArticleFavorite($articleId, $uid)
    {
        return ArticleFavorite::query()->where('article_id', $articleId)->where('user_id', $uid)->first();
    }

}
