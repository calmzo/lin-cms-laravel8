<?php

namespace App\Services\Admin;

use App\Models\ArticleTag;
use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionService
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Question::query();

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

        if (isset($where['anonymous'])) {
            $query->where('anonymous', $where['anonymous']);
        }

        if (isset($where['closed'])) {
            $query->where('closed', $where['closed']);
        }

        if (isset($where['solved'])) {
            $query->where('solved', $where['solved']);
        }

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $query->whereIn('published', $where['published']);
            } else {
                $query->where('published', $where['published']);
            }
        }

        if ($sort == 'unanswered') {
            $query->where('answer_count', 0);
        }

        if ($sort == 'reported') {
            $query->where('report_count', '>', 0);
        }

        switch ($sort) {
            case 'like':
                $query->orderByDesc('last_reply_time');
                break;
            case 'score':
                $query->orderByDesc('score');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        list($page, $count) = paginateFormat($page, $count);
        return $query->paginate($count, ['*'], 'page', $page);
    }

    protected function getTagArticleIds($tagId)
    {
        $tagIds = is_array($tagId) ? $tagId : [$tagId];
        $rows = ArticleTag::query()->whereIn('tag_id', $tagIds)->get();

        $result = [];

        if ($rows->count() > 0) {
            $result = $rows->pluck('article_id');
        }

        return $result;
    }


}
