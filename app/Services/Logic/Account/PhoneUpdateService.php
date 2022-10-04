<?php

namespace App\Services\Logic\Account;

use App\Models\Account;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Validators\AccountValidator;
use App\Validators\VerifyValidator;

class PhoneUpdateService extends LogicService
{

    public function handle($params)
    {
        $phone = $params['phone'] ?? '';
        $loginPassword = $params['login_password'] ?? '';
        $verifyCode = $params['verify_code'] ?? '';
        $user = AccountLoginTokenService::user();
        $account = Account::query()->find($user->id);

        $accountValidator = new AccountValidator();

        $phone = $accountValidator->checkPhone($phone);
        if ($phone != $account->phone) {
            $accountValidator->checkIfPhoneTaken($phone);
        }

        $accountValidator->checkLoginPassword($account, $loginPassword);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($phone, $verifyCode);

        $account->phone = $phone;

        $account->save();

        return $account;
    }

}
