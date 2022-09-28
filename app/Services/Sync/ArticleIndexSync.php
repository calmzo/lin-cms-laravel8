<?php

namespace App\Services\Sync;


use Illuminate\Support\Facades\Redis;

class ArticleIndexSync
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($articleId)
    {
        $redis = Redis::connection();

        $key = $this->getSyncKey();

        $redis->sAdd($key, $articleId);

        if ($redis->sCard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_article_index';
    }

}
