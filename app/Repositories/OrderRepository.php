<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository
{
    public function findById($id)
    {
        return Order::query()->find($id);
    }

    public function findBySn($sn)
    {
        return Order::query()->where('sn', $sn)->first();
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Order::query();

        if (!empty($where['id'])) {
            $query->where('id', $where['id']);
        }

        if (!empty($where['sn'])) {
            $query->where('sn', $where['sn']);
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

        if (!empty($where['promotion_type'])) {
            if (is_array($where['promotion_type'])) {
                $query->whereIn('promotion_type', $where['promotion_type']);
            } else {
                $query->where('promotion_type', $where['promotion_type']);
            }
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
