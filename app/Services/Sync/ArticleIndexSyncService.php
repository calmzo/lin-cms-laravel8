<?php

namespace App\Services\Sync;

use App\Services\BaseService;

class ArticleIndexSyncService extends BaseService
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($articleId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sadd($key, $articleId);

        if ($redis->scard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_article_index';
    }

}
