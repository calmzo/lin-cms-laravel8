<?php

namespace App\Caches;
use App\Lib\AppInfo;

class AppInfoCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "_APP_INFO_";
    }

    public function getContent($id = null)
    {
        $appInfo = new AppInfo();

        return [
            'name' => $appInfo->name ?? '',
            'alias' => $appInfo->alias ?? '',
            'link' => $appInfo->link ?? '',
            'version' => $appInfo->version ?? '',
        ];
    }

}
