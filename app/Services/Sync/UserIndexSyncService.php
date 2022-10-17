<?php

namespace App\Services\Sync;

use App\Services\BaseService;

class UserIndexSyncService extends BaseService
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($userId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sadd($key, $userId);

        if ($redis->scard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_user_index';
    }

}
