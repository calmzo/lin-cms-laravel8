<?php

namespace App\Services\Logic\Question;

use App\Caches\CategoryTreeListCache;
use App\Enums\CategoryEnums;
use App\Services\Logic\LogicService;

class CategoryListService extends LogicService
{

    public function handle()
    {
        $cache = new CategoryTreeListCache();

        return $cache->get(CategoryEnums::TYPE_QUESTION);
    }

}
