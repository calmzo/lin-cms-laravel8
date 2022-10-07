<?php

namespace App\Services\Sync;

use App\Models\Learning;
use App\Services\BaseService;
use App\Traits\ClientTrait;

class LearningSync extends BaseService
{

    use ClientTrait;

    /**
     * @var int
     */
    protected $lifetime = 86400;

    /**
     * @param Learning $learning
     * @param int $intervalTime
     */
    public function addItem(Learning $learning, $intervalTime = 10)
    {
        $cache = $this->getCache();

        $redis = $this->getRedis();

        $itemKey = $this->getItemKey($learning->request_id);

        /**
         * @var Learning $cacheLearning
         */
        $cacheLearning = $cache->get($itemKey);

        if (!$cacheLearning) {

            $learning->client_type = $this->getClientType();
            $learning->client_ip = $this->getClientIp();
            $learning->duration = $intervalTime;
            $learning->active_time = time();

            $cache->put($itemKey, $learning, $this->lifetime);

        } else {

            $cacheLearning->duration += $intervalTime;
            $cacheLearning->position = $learning->position;
            $cacheLearning->active_time = time();

            $cache->put($itemKey, $cacheLearning, $this->lifetime);
        }

        $key = $this->getSyncKey();

        $redis->sadd($key, $learning->request_id);

        if ($redis->scard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getItemKey($id)
    {
        return "learning:{$id}";
    }

    public function getSyncKey()
    {
        return 'sync_learning';
    }

}
