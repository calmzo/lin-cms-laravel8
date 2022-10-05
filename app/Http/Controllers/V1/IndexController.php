<?php

namespace App\Http\Controllers\V1;

use App\Caches\IndexSlideListCache;

class IndexController extends BaseController
{
    //
    protected $only = [];

    public function getSlides()
    {
        $cache = new IndexSlideListCache();

        $slides = $cache->get();

        return $this->success(['slides' => $slides]);

    }
}
