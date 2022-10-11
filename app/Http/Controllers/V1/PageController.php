<?php

namespace App\Http\Controllers\V1;

use App\Services\PageService;

class PageController extends BaseController
{
    //
    protected $only = [];

    public function getPage($id)
    {
        $service = new PageService();
        $res = $service->getPage($id);
        return $this->success($res);

    }
}
