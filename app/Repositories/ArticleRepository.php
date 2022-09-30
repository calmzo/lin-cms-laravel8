<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleRepository extends BaseRepository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Article::query();

        $fakeId = false;

        if (!empty($where['tag_id'])) {
            $where['id'] = $this->getTagArticleIds($where['tag_id']);
            $fakeId = empty($where['id']);
        }

        /**
         * 构造空记录条件
         */
        if ($fakeId) {
            $where['id'] = -999;
        }

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $query->whereIn('id', $where['id']);
            } else {
                $query->where('id', $where['id']);
            }
        }

        if (!empty($where['category_id'])) {
            if (is_array($where['category_id'])) {
                $query->whereIn('category_id', $where['category_id']);
            } else {
                $query->where('category_id', $where['category_id']);
            }
        }

        if (!empty($where['user_id'])) {
            $query->where('user_id', $where['user_id']);
        }

        if (isset($where['source_type'])) {
            if (is_array($where['source_type'])) {
                $query->whereIn('source_type', $where['source_type']);
            } else {
                $query->where('source_type', $where['source_type']);
            }
        }

        if (!empty($where['title'])) {
            $query->where('title', 'like', '%' . $where['title'] . '%');
        }

        if (isset($where['private'])) {
            $query->where('private', $where['private']);
        }

        if (isset($where['featured'])) {
            $query->where('featured', $where['featured']);
        }

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $query->whereIn('published', $where['published']);
            } else {
                $query->where('published', $where['published']);
            }
        }

        if (isset($where['closed'])) {
            $query->where('closed', $where['closed']);
        }


        if ($sort == 'featured') {
            $query->where('featured', 1);
        }

        if ($sort == 'reported') {
            $query->where('report_count', '>', 0);
        }

        switch ($sort) {
            case 'like':
                $query->orderByDesc('like_count');
                break;
            case 'popular':
                $query->orderByDesc('score');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query->paginate($count, ['*'], 'page', $page);
    }

    protected function getTagArticleIds($tagId)
    {
        $tagIds = is_array($tagId) ? $tagId : [$tagId];
        $repo = new ArticleTagRepository();
        $rows = $repo->findByTagIds($tagIds);
        $result = [];

        if ($rows->count() > 0) {
            $result = $rows->pluck('article_id');
        }

        return $result;
    }

}
