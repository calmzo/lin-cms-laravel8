<?php

namespace App\Lib\Validators;

use App\Exceptions\BadRequestException;
use App\Lib\Validators\Common as CommonValidator;
use App\Services\VerifyService;
use App\Utils\CodeResponse;

class Verify
{

    public function checkPhone($phone)
    {
        if (!CommonValidator::phone($phone)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'verify.invalid_phone');
        }

        return $phone;
    }

    public function checkEmail($email)
    {
        if (!CommonValidator::email($email)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'verify.invalid_email');
        }

        return $email;
    }

    public function checkCode($identity, $code)
    {
        if (CommonValidator::email($identity)) {
            $this->checkMailCode($identity, $code);
        } elseif (CommonValidator::phone($identity)) {
            $this->checkSmsCode($identity, $code);
        } else {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'verify.invalid_code');
        }
    }

    public function checkSmsCode($phone, $code)
    {
        $service = new VerifyService();

        $result = $service->checkSmsCode($phone, $code);

        if (!$result) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'verify.invalid_sms_code');
        }
    }

    public function checkMailCode($email, $code)
    {
        $service = new VerifyService();

        $result = $service->checkMailCode($email, $code);

        if (!$result) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'verify.invalid_mail_code');
        }
    }

    public function checkRand($rand)
    {
        list($time, $number) = explode('-', $rand);

        if (abs($time - time()) > 300) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'verify.invalid_rand');
        }

        if ($number < 1000 || $number > 9999) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'verify.invalid_rand');
        }

        return $rand;
    }

    public function checkTicket($ticket, $rand)
    {
        $ticket = $this->crypt->decrypt($ticket);

        if ($ticket != $rand) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'verify.invalid_ticket');
        }
    }

}
