<?php

namespace App\Http\Controllers\V1;

use App\Services\FlashSaleService;
use Illuminate\Http\Request;

class FlashSaleController extends BaseController
{
    //
    protected $only = [];

    public function getFlashSales()
    {
        $service = new FlashSaleService();
        $result = $service->getFlashSales();
        return $this->success($result);
    }


    public function createOrder(Request $request)
    {
        $params = $request->all();
        $service = new FlashSaleService();
        $result = $service->createOrder($params);
        return $this->success($result);
    }
}
