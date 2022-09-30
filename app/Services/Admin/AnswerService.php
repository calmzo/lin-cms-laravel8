<?php

namespace App\Services\Admin;

use App\Models\Answer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AnswerService
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Answer::query();

        if (!empty($where['id'])) {
            $query->where('id', $where['id']);
        }


        if (!empty($where['user_id'])) {
            $query->where('user_id', $where['user_id']);
        }

        if (!empty($where['question_id'])) {
            $query->where('question_id', $where['question_id']);

        }


        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $query->whereIn('published', $where['published']);
            } else {
                $query->where('published', $where['published']);
            }
        }

        if ($sort == 'reported') {
            $query->where('report_count', '>', 0);
        }

        switch ($sort) {
            case 'popular':
                $query->orderByDesc('like_count');
                break;
            case 'accepted':
                $query->orderByDesc('accepted')->orderByDesc('like_count');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        list($page, $count) = paginateFormat($page, $count);
        return $query->paginate($count, ['*'], 'page', $page);
    }


}
