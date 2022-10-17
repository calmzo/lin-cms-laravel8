<?php

namespace App\Services\Sync;

use App\Services\BaseService;

class QuestionScoreSyncService extends BaseService
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($questionId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sadd($key, $questionId);

        if ($redis->scard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_question_score';
    }

}
