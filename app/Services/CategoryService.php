<?php

namespace App\Services;

use App\Caches\CategoryTreeListCache;
use App\Enums\CategoryEnums;

class CategoryService
{
    public function categorieTreeList()
    {
        $cache = new CategoryTreeListCache();
        return $cache->get(CategoryEnums::TYPE_ARTICLE);
    }
}
