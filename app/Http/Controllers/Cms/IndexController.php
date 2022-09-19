<?php

namespace App\Http\Controllers\Cms;

use App\Services\Admin\IndexService;

/**
 *
 */
class IndexController extends BaseController
{
    protected $only = [];


    /**
     * 统计
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function main()
    {
        $service = new IndexService();
        $res = $service->getMain();
        return $this->success($res);
    }
}
