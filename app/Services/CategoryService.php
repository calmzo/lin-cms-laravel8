<?php

namespace App\Services;

use App\Caches\CategoryCache;
use App\Caches\CategoryListCache;
use App\Caches\CategoryTreeListCache;
use App\Enums\CategoryEnums;

class CategoryService
{
    public function categorieTreeList()
    {
        $cache = new CategoryTreeListCache();
        return $cache->get(CategoryEnums::TYPE_ARTICLE);
    }

    public function getChildCategoryIds($id)
    {
        $categoryCache = new CategoryCache();

        /**
         * @var Category $category
         */
        $category = $categoryCache->get($id);

        if (!$category) {
            return [];
        }

        if ($category->level == 2) {
            return [$id];
        }

        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get($category->type);

        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $id) {
                $result[] = $category['id'];
            }
        }

        return $result;
    }
}
