<?php

namespace App\Caches;

use App\Repositories\PageRepository;

class PageCache extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "page:{$id}";
    }

    public function getContent($id = null)
    {
        $pageRepo = new PageRepository();

        $page = $pageRepo->findById($id);

        return $page ?: null;
    }

}
