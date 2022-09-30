<?php

namespace App\Services\Logic\Account;

use App\Models\Account;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Lib\Validators\AccountValidator;
use App\Lib\Validators\VerifyValidator;

class EmailUpdateService extends LogicService
{

    public function handle($params)
    {
        $email = $params['email'] ?? '';
        $loginPassword = $params['login_password'] ?? '';
        $verifyCode = $params['verify_code'] ?? '';
        $user = AccountLoginTokenService::user();

        $account = Account::query()->find($user->id);

        $accountValidator = new AccountValidator();

        $email = $accountValidator->checkEmail($email);

        if ($email != $account->email) {
            $accountValidator->checkIfEmailTaken($email);
        }

        $accountValidator->checkLoginPassword($account, $loginPassword);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($email, $verifyCode);

        $account->email = $email;

        $account->save();

        return $account;
    }

}
