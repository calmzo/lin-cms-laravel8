<?php

namespace App\Repositories;

use App\Models\Package;

class PackageRepository extends BaseRepository
{

    public function countPackages()
    {
        return Package::query()->where('published', 1)->count();
    }
}
