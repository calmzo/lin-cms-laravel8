<?php

namespace App\Repositories;

use App\Models\PointHistory;

class PointHistoryRepository extends BaseRepository
{
    /**
     * @param $eventId
     * @param $eventType
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findEventHistory($eventId, $eventType)
    {
        return PointHistory::query()->where('event_id', $eventId)->where('event_type', $eventType)->first();
    }

    /**
     * @param $userId
     * @param $eventType
     * @param $date
     * @return int|mixed
     */
    public function sumUserDailyEventPoints($userId, $eventType, $date)
    {
        return PointHistory::query()->where('user_id', $userId)->where('event_type', $eventType)->where('create_time', '>', $date)->sum('event_point');
    }

}
