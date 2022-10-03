<?php

namespace App\Repositories;

use App\Models\Online;

class OnlineRepository extends BaseRepository
{
    public function findByUserDate($userId, $activeDate)
    {
        $startTime = strtotime($activeDate);

        $endTime = $startTime + 86400;

        return Online::query()
            ->where('user_id', $userId)
            ->whereBetween('active_time', [$startTime, $endTime])
            ->get();
    }

}
