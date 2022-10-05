<?php

namespace App\Services\Logic\Help;

use App\Caches\HelpListCache;
use App\Services\Logic\LogicService;

class HelpListService extends LogicService
{

    public function handle()
    {
        $cache = new HelpListCache();

        return $cache->get();
    }

}
