<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository extends BaseRepository
{
    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Notification::query();

        if (!empty($where['sender_id'])) {
            $query->where('sender_id', $where['sender_id']);
        }

        if (!empty($where['receiver_id'])) {
            $query->where('receiver_id', $where['receiver_id']);
        }

        if (!empty($where['event_id'])) {
            $query->where('event_id', $where['event_id']);
        }

        if (!empty($where['event_type'])) {
            if (is_array($where['event_type'])) {
                $query->whereIn('event_type', $where['event_type']);
            } else {
                $query->where('event_type', $where['event_type']);
            }
        }

        if (isset($where['viewed'])) {
            $query->where('viewed', $where['viewed']);
        }

        switch ($sort) {
            case 'oldest':
                $query->orderBy('id');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query->paginate($count, ['*'], 'page', $page);
    }

}
