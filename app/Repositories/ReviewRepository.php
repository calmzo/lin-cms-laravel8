<?php

namespace App\Repositories;

use App\Enums\ReviewEnums;
use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewRepository extends BaseRepository
{
    public function countReviews()
    {
        return Review::query()->where('published', ReviewEnums::PUBLISH_APPROVED)->count();
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Review::query();

        if (!empty($where['id'])) {
            $query->where('id', $where['id']);
        }

        if (!empty($where['course_id'])) {
            $query->where('course_id', $where['course_id']);
        }

        if (!empty($where['user_id'])) {
            $query->where('user_id', $where['user_id']);
        }

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $query->whereIn('published', $where['published']);
            } else {
                $query->where('published', $where['published']);
            }
        }

        if (isset($where['rating'])) {
            switch ($where['rating']) {
                case 'good':
                    $query->where('rating', 5);
                    break;
                case 'normal':
                    $query->whereBetween('rating', [3, 4]);
                    break;
                case 'bad':
                    $query->where('rating', '<', 3);
                    break;
            }
        }

        switch ($sort) {
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query->paginate($count, ['*'], 'page', $page);
    }

}
