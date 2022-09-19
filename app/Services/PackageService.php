<?php

namespace App\Services;

use App\Models\Package;

class PackageService
{

    public function countPackages()
    {
        return Package::query()->where('published', 1)->count();
    }
}
