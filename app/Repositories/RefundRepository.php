<?php

namespace App\Repositories;

use App\Models\Refund;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RefundRepository extends BaseRepository
{

    public function findById($id)
    {
        return Refund::query()->find($id);
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Refund::query();

        if (!empty($where['user_id'])) {
            $query->where('user_id', $where['user_id']);
        }

        if (!empty($where['order_id'])) {
            $query->where('order_id', $where['order_id']);
        }

        if (!empty($where['status'])) {
            if (is_array($where['status'])) {
                $query->whereIn('status', $where['status']);
            } else {
                $query->where('status', $where['status']);
            }
        }

        if (!empty($where['start_time']) && !empty($where['end_time'])) {
            $query->whereBetween('create_time', [$where['start_time'], $where['end_time']]);
        }

        switch ($sort) {
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query->paginate($count, ['*'], 'page', $page);
    }
}
