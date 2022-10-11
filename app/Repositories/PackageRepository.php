<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Package;

class PackageRepository extends BaseRepository
{

    public function findById($id)
    {
        return Package::query()->find($id);
    }

    public function countPackages()
    {
        return Package::query()->where('published', 1)->count();
    }

    public function findCourses($packageId)
    {
        return Course::query()
            ->where('published', 1)
            ->whereHas('packages', function ($q) use ($packageId) {
                $q->where('package_id', $packageId);
            })
            ->get();
    }
}
