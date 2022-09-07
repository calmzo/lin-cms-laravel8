<?php

namespace App\Http\Controllers\V1;

use App\Services\AccountService;
use App\Services\Logic\Verify\MailCodeService;
use App\Services\Logic\Verify\SmsCodeService;
use Illuminate\Http\Request;

class VerifyController extends BaseController
{


    public function smsCode(Request $request)
    {
        $params = $request->all();
        $service = new SmsCodeService();
        $service->handle($params);
        return $this->success();
    }


    public function mailCode(Request $request)
    {
        $params = $request->all();
        $service = new MailCodeService();
        $service->handle($params);

        return $this->success();
    }



}
