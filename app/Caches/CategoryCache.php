<?php

namespace App\Caches;

use App\Models\Category;

class CategoryCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "category:{$id}";
    }

    public function getContent($id = null)
    {
        $category = Category::query()->find($id);

        return $category ?: null;
    }

}
