<?php

namespace App\Caches;

use App\Models\Page;

class MaxPageIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_page_id';
    }

    public function getContent($id = null)
    {
        $page = Page::query()->latest('id')->first();;

        return $page->id ?? 0;
    }

}
