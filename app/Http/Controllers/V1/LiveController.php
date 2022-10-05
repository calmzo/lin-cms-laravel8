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
}
