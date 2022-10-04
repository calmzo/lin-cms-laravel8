<?php

namespace App\Services\Logic\Account;

use App\Validators\AccountValidator;
use App\Models\Account;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateService extends LogicService
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
