<?php

namespace App\Http\Controllers\V1;

use App\Services\RefundService;
use Illuminate\Http\Request;

class RefundController extends BaseController
{
    //
    protected $only = ['getRefund'];

    public function getConfirm(Request $request)
    {
        $sn = $request->input('sn', '');
        $service = new RefundService();
        $question = $service->getConfirm($sn);
        return $this->success($question);
    }

    public function getRefund(Request $request)
    {
        $sn = $request->input('sn', '');
        $service = new RefundService();
        $question = $service->getRefund($sn);
        return $this->success($question);
    }

    public function createRefund()
    {
        $service = new RefundService();
        $question = $service->createRefund();
        return $this->success($question);
    }
}
