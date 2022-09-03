<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Cms\BaseController;
use App\Services\TradeService;
use Illuminate\Http\Request;

class TradeController extends BaseController
{
    public $except = [];

    public function createQrcodeTrade(Request $request)
    {
        $params = $request->all();
        $tradeService = new TradeService();
        $res = $tradeService->createQrcodeTrade($params);
        return $this->success($res);

    }


    public function createH5Trade(Request $request)
    {
        $params = $request->all();
        $tradeService = new TradeService();
        $res = $tradeService->createH5Trade($params);
        return $this->success($res);
    }

    public function createMiniTrade(Request $request)
    {
        $params = $request->all();
        $tradeService = new TradeService();
        $res = $tradeService->createMiniTrade($params);
        return $this->success($res);
    }

    public function refund(Request $request, $id)
    {
        $params = $request->all();
        $tradeService = new TradeService();
        $res = $tradeService->refundTrade($id, $params);
        return $this->success($res);
    }
}
