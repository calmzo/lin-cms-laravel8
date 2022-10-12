<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

trait ServiceTrait
{


    /**
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function getCache()
    {
        return Cache::store('redis');
    }

    /**
     * @return \Illuminate\Redis\Connections\Connection
     */
    public function getRedis()
    {
        return Redis::connection();
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\Request|\Laravel\Lumen\Application|string|\think\App|\think\facade\Request|null
     */
    public function getRequest()
    {
        return request();
    }



}
