<?php

namespace App\Services;

class VerifyService extends Service
{

    protected $cache;

    public function __construct()
    {
        $this->cache = $this->getCache();
    }

    public function getSmsCode($phone, $lifetime = 300)
    {
        $key = $this->getSmsCacheKey($phone);

        $code = rand(100000, 999999);
        $this->cache->set($key, $code, $lifetime);

        return $code;
    }

    public function getMailCode($email, $lifetime = 300)
    {
        $key = $this->getMailCacheKey($email);

        $code = rand(100000, 999999);
        $this->cache->set($key, $code, $lifetime);

        return $code;
    }

    public function checkSmsCode($phone, $code)
    {
        $key = $this->getSmsCacheKey($phone);

        $value = $this->cache->get($key);

        return $code == $value;
    }

    public function checkMailCode($email, $code)
    {
        $key = $this->getMailCacheKey($email);

        $value = $this->cache->get($key);

        return $code == $value;
    }

    protected function getMailCacheKey($email)
    {
        return "verify:mail:{$email}";
    }

    protected function getSmsCacheKey($phone)
    {
        return "verify:sms:{$phone}";
    }

}
