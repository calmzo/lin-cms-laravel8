<?php

namespace App\Caches;

class SettingCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "setting:{$id}";
    }

    public function getContent($id = null)
    {

        $items = config('site');

//        $result = [];
//
//        foreach ($items as $item) {
//            $result[$item->item_key] = $item->item_value;
//        }
        return $items;
    }

}
