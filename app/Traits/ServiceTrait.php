<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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


}
