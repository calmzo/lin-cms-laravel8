<?php

namespace App\Caches;

use App\Models\Package;

class MaxPackageIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_package_id';
    }

    public function getContent($id = null)
    {
        $package = Package::query()->latest('id')->first();

        return $package->id ?? 0;
    }

}
