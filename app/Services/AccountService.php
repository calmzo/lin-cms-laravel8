<?php

namespace App\Services;

use App\Events\AccountLoginEvent;
use App\Events\AccountLogoutEvent;
use App\Events\AccountRegisterEvent;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Logic\Account\RegisterService;
use App\Services\Token\AccountLoginTokenService;
use App\Lib\Validators\AccountValidator;

class AccountService
{

    /**
     * 注册
     * @param $params
     */
    public function register($params)
    {
        $service = new RegisterService();

        $account = $service->handle($params);
        $userRepo = new UserRepository();
        $user = $userRepo->findById($account->id);
        $token = AccountLoginTokenService::getToken($user);
        AccountRegisterEvent::dispatch($user);
        return $token;
    }

    /**
     * 密码登录
     * @param $params
     */
    public function loginByPassword($params)
    {

        /**
         * 使用[account|phone|email]做账户名字段兼容
         */
        if (isset($params['phone'])) {
            $params['account'] = $params['phone'];
        } elseif (isset($params['email'])) {
            $params['account'] = $params['email'];
        }
        $account = $params['account'];
        $password = $params['password'];

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($account, $password);

//        $validator->checkIfAllowLogin($user);
        $token = AccountLoginTokenService::getToken($user);
        AccountLoginEvent::dispatch($user);
        return $token;
    }

    /**
     * 验证码登录
     * @param $params
     */
    public function loginByVerify($params)
    {

        /**
         * 使用[account|phone|email]做账户名字段兼容
         */
        if (isset($params['phone'])) {
            $params['account'] = $params['phone'];
        } elseif (isset($params['email'])) {
            $params['account'] = $params['email'];
        }

        $account = $params['account'];
        $verifyCode = $params['verify_code'];
        $validator = new AccountValidator();

        $user = $validator->checkVerifyLogin($account, $verifyCode);

//        $validator->checkIfAllowLogin($user);
        $token = AccountLoginTokenService::getToken($user);
        AccountLoginEvent::dispatch($user);

        return $token;
    }

    /**
     * 登出
     */
    public function logout()
    {
        $user = AccountLoginTokenService::user();
        AccountLoginTokenService::logout();
        AccountLogoutEvent::dispatch($user);
    }

}
