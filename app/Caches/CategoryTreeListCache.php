<?php

namespace App\Caches;

use App\Builders\CategoryTreeListBuilder;

class CategoryTreeListCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "category_tree_list:{$id}";
    }

    public function getContent($id = null)
    {
        $builder = new CategoryTreeListBuilder();

        $list = $builder->handle($id);

        return $list ?: [];
    }

}
