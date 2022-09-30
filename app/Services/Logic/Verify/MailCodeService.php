<?php

namespace App\Services\Logic\Verify;

use App\Lib\Notice\Mail\Verify as MailVerifyService;
use App\Lib\Validators\VerifyValidator;
use App\Services\Logic\LogicService;

class MailCodeService extends LogicService
{

    public function handle($params)
    {
        $email = $params['email'];
        $validator = new VerifyValidator();
        $email = $validator->checkEmail($email);
//        $captcha = $this->getSettings('captcha');
//
//        if ($captcha['enabled'] == 1) {
//
//            $validator = new CaptchaValidator();
//
//            $validator->checkCode($post['captcha']['ticket'], $post['captcha']['rand']);
//        }

        $service = new MailVerifyService;

        $service->handle($email);
    }

}
