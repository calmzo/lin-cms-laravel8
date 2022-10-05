<?php

namespace App\Repositories;

use App\Models\ArticleFavorite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleFavoriteRepository extends BaseRepository
{
    public function findArticleFavorite($articleId, $uid)
    {
        return ArticleFavorite::withTrashed()->where('article_id', $articleId)->where('user_id', $uid)->first();
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15): LengthAwarePaginator
    {
        $query = ArticleFavorite::query();

        if (!empty($where['article_id'])) {
            $query->where('article_id', $where['article_id']);
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
