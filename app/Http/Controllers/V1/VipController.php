<?php

namespace App\Http\Controllers\V1;

use App\Services\VipService;

class VipController extends BaseController
{
    //
    protected $only = [];

    public function getCourses()
    {
        $service = new VipService();
        $pager = $service->getCourses();
        return $this->successPaginate($pager);
    }

    public function getUsers()
    {
        $service = new VipService();
        $pager = $service->getUsers();
        return $this->successPaginate($pager);
    }

}
