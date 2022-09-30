<?php

namespace App\Caches;

use App\Models\Category;

class CategoryListCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "category_list:{$id}";
    }

    /**
     * @param null $id
     * @return array
     */
    public function getContent($id = null)
    {
        /**
         *
         */
        $categories = Category::query()
            ->where('type', $id)
            ->where('published', 1)
            ->orderBy('level')
            ->orderBy('priority')
            ->get(['id', 'parent_id', 'name', 'priority', 'level', 'path']);

        if ($categories->count() == 0) {
            return [];
        }

        return $categories->toArray();
    }

}
