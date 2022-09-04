<?php

namespace App\Caches;

use Illuminate\Support\Facades\Redis;

abstract class Cache
{

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $redis;

    public function __construct()
    {
        $this->cache = \Illuminate\Support\Facades\Cache::store('redis');
        $this->redis = Redis::connection();
    }

    /**
     * 获取缓存内容
     * @param $key
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get($id = null)
    {
        $key = $this->getKey($id);

        $lifetime = $this->getLifetime();
        return $this->cache->remember($key, $lifetime, function () use ($id){
            return $this->getContent($id);
        });
    }

    /**
     * 删除缓存内容
     *
     * @param mixed $id
     */
    public function delete($id)
    {
        $key = $this->getKey($id);

        return $this->cache->forget($key);
    }


    /**
     * 重建缓存内容
     *
     * @param mixed $id
     */
    public function rebuild($id = null)
    {
        $this->delete($id);

        $this->get($id);
    }


    public function hGet($id, $hashKey)
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hGet($key, $hashKey);
    }

    public function hDel($id, $hashKey)
    {
        $key = $this->getKey($id);

        return $this->redis->hDel($key, $hashKey);
    }

    public function hIncrBy($id, $hashKey, $value = 1)
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        $this->redis->hIncrBy($key, $hashKey, $value);
    }

    public function hDecrBy($id, $hashKey, $value = 1)
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        $this->redis->hIncrBy($key, $hashKey, 0 - $value);
    }


    /**
     * 获取缓存有效期
     *
     * @return int
     */
    abstract public function getLifetime();

    /**
     * 获取键值
     *
     * @param mixed $id
     * @return string
     */
    abstract public function getKey($id = null);

    /**
     * 获取原始内容
     *
     * @param mixed $id
     * @return mixed
     */
    abstract public function getContent($id = null);

}
