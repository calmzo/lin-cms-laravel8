<?php

namespace App\Repositories;

use App\Models\Report;

class ReportRepository extends BaseRepository
{
    public function findUserReport($userId, $itemId, $itemType)
    {
        return Report::query()
            ->where('user_id', $userId)
            ->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->first();
    }

}
