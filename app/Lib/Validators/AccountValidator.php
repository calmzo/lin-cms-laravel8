<?php

namespace App\Lib\Validators;

use App\Exceptions\BadRequestException;
use App\Exceptions\Token\ForbiddenException;
use App\Traits\ClientTrait;
use App\Models\User;
use App\Models\Account;
use App\Utils\CodeResponse;
use Illuminate\Support\Facades\Hash;

class AccountValidator
{

    use ClientTrait;

    public function checkAccount($name)
    {
        $account = null;
        if (CommonValidator::email($name)) {
            $account = Account::query()->where('email', $name)->first();
        } elseif (CommonValidator::phone($name)) {
            $account = Account::query()->where('phone', $name)->first();
        } elseif (CommonValidator::intNumber($name)) {
            $account = Account::query()->where('id', $name)->first();
        }

        if (!$account) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.not_found');
        }

        return $account;
    }

    public function checkLoginName($name)
    {
        $isPhone = CommonValidator::phone($name);
        $isEmail = CommonValidator::email($name);

        $loginNameOk = $isPhone || $isEmail;

        if (!$loginNameOk) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.invalid_login_name');
        }
    }

    public function checkPhone($phone)
    {
        if (!CommonValidator::phone($phone)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.invalid_phone');
        }

        return $phone;
    }

    public function checkEmail($email)
    {
        if (!CommonValidator::email($email)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.invalid_email');
        }

        return $email;
    }

    public function checkPassword($password)
    {
        if (!CommonValidator::password($password)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.invalid_pwd');
        }

        return $password;
    }

    public function checkConfirmPassword($newPassword, $confirmPassword)
    {
        if ($newPassword != $confirmPassword) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.pwd_not_match');
        }
    }

    public function checkOriginPassword(Account $account, $password)
    {
        $hash = Hash::make($password);

        if ($hash != $account->password) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.origin_pwd_incorrect');
        }
    }

    public function checkLoginPassword(Account $account, $password)
    {
        $hash = Hash::make($password);
        if ($hash != $account->password) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.login_pwd_incorrect');
        }
    }

    public function checkIfPhoneTaken($phone)
    {
        $account = Account::query()->where('phone', $phone)->first();

        if ($account) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.phone_taken');
        }
    }

    public function checkIfEmailTaken($email)
    {
        $account = Account::query()->where('email', $email)->first();

        if ($account) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.email_taken');
        }
    }

    public function checkVerifyLogin($name, $code)
    {
        $this->checkLoginName($name);

        $account = $this->checkAccount($name);

        $verify = new VerifyValidator();

        $verify->checkCode($name, $code);

        return User::query()->find($account->id);
    }

    public function checkUserLogin($name, $password)
    {
        $this->checkLoginName($name);

        $account = $this->checkAccount($name);

        $hash = Hash::make($password);
        if ($hash != $account->password) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'account.login_pwd_incorrect');
        }

        return User::query()->find($account->id);
    }

    public function checkIfAllowLogin(User $user)
    {
        $case1 = $user->locked == 1;
        $case2 = $user->lock_expiry_time > time();

        if ($case1 && $case2) {
            throw new ForbiddenException(CodeResponse::FORBIDDEN_EXCEPTION, 'account.login_pwd_incorrect');
        }

        $this->checkFloodLogin($user->id);
    }

//    public function checkFloodLogin($userId)
//    {
//        $clientIp = $this->getClientIp();
//        $clientType = $this->getClientType();
//
//        if ($clientType == ClientEnums::TYPE_PC) {
//            $records = $repo->findUserRecentSessions($userId, 10);
//        } else {
//            $records = $repo->findUserRecentTokens($userId, 10);
//        }
//
//        if ($records->count() == 0) return;
//
//        $clientIps = array_column($records->toArray(), 'client_ip');
//
//        $countValues = array_count_values($clientIps);
//
//        foreach ($countValues as $ip => $count) {
//            if ($clientIp == $ip && $count > 4) {
//                throw new ForbiddenException(CodeResponse::FORBIDDEN_EXCEPTION, 'account.flood_login');
//            }
//        }
//    }

}
