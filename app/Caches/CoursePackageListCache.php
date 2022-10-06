<?php

namespace App\Caches;

use App\Models\Package;
use App\Repositories\CourseRepository;

class CoursePackageListCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_package_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepository();

        $packages = $courseRepo->findPackages($id);

        if ($packages->count() == 0) {
            return [];
        }

        return $this->handleContent($packages);
    }

    /**
     * @param Package[] $packages
     * @return array
     */
    protected function handleContent($packages)
    {
        $result = [];

        foreach ($packages as $package) {
            $result[] = [
                'id' => $package->id,
                'title' => $package->title,
                'course_count' => $package->course_count,
                'market_price' => $package->market_price,
                'vip_price' => $package->vip_price,
            ];
        }

        return $result;
    }

}
