<?php

namespace App\Caches;

use App\Repositories\PackageRepository;

class PackageCache extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "package:{$id}";
    }

    public function getContent($id = null)
    {
        $packageRepo = new PackageRepository();

        $package = $packageRepo->findById($id);

        return $package ?: null;
    }

}
