<?php

namespace App\Repositories;

use App\Models\PointGiftRedeem;

class PointGiftRedeemRepository extends BaseRepository
{

    public function findById($id)
    {
        return PointGiftRedeem::query()->find($id);
    }
}
