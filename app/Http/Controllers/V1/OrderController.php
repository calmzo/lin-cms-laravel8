<?php

namespace App\Http\Controllers\V1;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    //
    public $except = [];

    public function createOrder(Request $request)
    {
        $params = $request->all();
        $orderService = new OrderService();
        $res = $orderService->creatOrder($params);
        return $this->success($res);
    }

    public function confirmOrder(Request $request)
    {
        $itemId = $request->input('item_id', 0);
        $itemType = $request->input('item_type', 1);
        $orderService = new OrderService();
        $res = $orderService->confirmOrder($itemId, $itemType);
        return $this->success($res);
    }

}
