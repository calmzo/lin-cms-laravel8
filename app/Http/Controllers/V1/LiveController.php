<?php

namespace App\Http\Controllers\V1;

use App\Services\LiveService;
use App\Validates\Live\LiveListValidate;

class LiveController extends BaseController
{
    //
    protected $only = [];

    public function getLives(LiveListValidate $listValidate)
    {
        $params = $listValidate->check();
        $service = new LiveService();
        $list = $service->getLives($params);
        return $this->successPaginate($list);
    }

    public function getLiveChats($id)
    {
        $service = new LiveService();
        $res = $service->getLiveChats($id);
        return $this->success($res);
    }

    public function getLiveStats($id)
    {
        $service = new LiveService();
        $res = $service->getLiveStats($id);
        return $this->success($res);
    }

    public function getLiveStatus($id)
    {
        $service = new LiveService();
        $res = $service->getLiveStatus($id);
        return $this->success($res);
    }
}
