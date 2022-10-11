<?php

namespace App\Repositories;

use App\Models\Vip;

class VipRepository extends BaseRepository
{
    public function findById($id)
    {
        return Vip::query()->find($id);

    }

}
