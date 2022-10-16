<?php

namespace App\Repositories;

use App\Models\PointGift;

class PointGiftRepository extends BaseRepository
{
    public function findById($id)
    {
        return PointGift::query()->find($id);
    }

}
