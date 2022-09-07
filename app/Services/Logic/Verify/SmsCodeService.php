<?php

namespace App\Services\Logic\Verify;

use App\Services\CaptchaService;
use App\Lib\Notice\Sms\Verify;

class SmsCodeService
{

    public function handle($params)
    {

        $phone = $params['phone'] ?? '';
//        $captcha = $params['captcha'];
//
//        $ticket = $captcha['ticket'] ?? '';
//        $rand = $captcha['rand'] ?? '';
//
//        $captcha = config('captcha');
//
//        if ($captcha['enabled'] == 1) {
//            $validator = new CaptchaService();
//            $validator->verify($ticket, $rand);
//        }

        $service = new Verify();

        $service->handle($phone);
    }

}


