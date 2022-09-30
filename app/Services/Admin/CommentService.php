<?php

namespace App\Services\Admin;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentService
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Comment::query();

        if (!empty($where['id'])) {
            $query->where('id', $where['id']);
        }


        if (!empty($where['user_id'])) {
            $query->where('user_id', $where['user_id']);
        }

        if (!empty($where['item_id'])) {
            $query->where('item_id', $where['item_id']);

        }


        if (!empty($where['item_type'])) {
            if (is_array($where['item_type'])) {
                $query->whereIn('item_type', $where['item_type']);
            } else {
                $query->where('item_type', $where['item_type']);
            }
        }

        if (!empty($where['parent_id'])) {
            $query->where('parent_id', $where['parent_id']);

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
            default:
                $query->orderByDesc('id');
                break;
        }

        list($page, $count) = paginateFormat($page, $count);
        return $query->paginate($count, ['*'], 'page', $page);
    }


}
