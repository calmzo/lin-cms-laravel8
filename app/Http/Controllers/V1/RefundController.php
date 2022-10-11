<?php

namespace App\Http\Controllers\V1;

use App\Services\RefundService;
use Illuminate\Http\Request;

class RefundController extends BaseController
{
    //
    protected $only = [];

    public function getConfirm(Request $request)
    {
        $sn = $request->input('sn', '');
        $service = new RefundService();
        $question = $service->getConfirm($sn);
        return $this->success($question);
    }
}
