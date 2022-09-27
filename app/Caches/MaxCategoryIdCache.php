<?php

namespace App\Caches;

use App\Models\Category;

class MaxCategoryIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_category_id';
    }

    public function getContent($id = null)
    {
        $category = Category::query()->latest('id')->first();

        return $category->id ?? 0;
    }

}
