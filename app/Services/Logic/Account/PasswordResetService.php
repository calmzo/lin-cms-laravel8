<?php

namespace App\Services\Logic\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Lib\Validators\AccountValidator;
use App\Validators\Verify as VerifyValidator;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{

    public function handle($params)
    {
        $name = $params['account'] ?? '';
        $newPassword = $params['new_password'] ?? '';
        $verifyCode = $params['verify_code'] ?? '';
        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkAccount($name);

        $newPassword = $accountValidator->checkPassword($newPassword);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($name, $verifyCode);

        $password = Hash::make($newPassword);

        $account->password = $password;

        $account->save();

        return $account;
    }

}
