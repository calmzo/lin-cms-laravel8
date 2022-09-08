<?php

namespace App\Services\Logic\Account;

use App\Lib\Validators\AccountValidator;
use App\Models\Account;
use App\Services\Token\AccountLoginTokenService;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateService
{

    public function handle($params)
    {
        $originPassword = $params['origin_password'] ?? '';
        $newPassword = $params['new_password'] ?? '';
        $confirmPassword = $params['confirm_password'] ?? '';
        $user = AccountLoginTokenService::user();
        $account = Account::query()->find($user->id);

        $accountValidator = new AccountValidator();

        $accountValidator->checkOriginPassword($account, $originPassword);

        $newPassword = $accountValidator->checkPassword($newPassword);

        $accountValidator->checkConfirmPassword($newPassword, $confirmPassword);

        $password = Hash::make($newPassword);

        $account->password = $password;

        $account->save();

        return $account;
    }

}
