<?php

namespace App\Repositories;

use App\Models\WechatSubscribe;

class WechatSubscribeRepository extends BaseRepository
{
    public function findByUserId($userId)
    {
        return WechatSubscribe::query()->where('user_id', $userId)->first();
    }

}
