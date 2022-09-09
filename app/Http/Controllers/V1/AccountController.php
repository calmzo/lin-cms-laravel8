<?php

namespace App\Http\Controllers\V1;

use App\Services\AccountService;
use App\Services\Logic\Account\EmailUpdateService;
use App\Services\Logic\Account\PasswordResetService;
use App\Services\Logic\Account\PasswordUpdateService;
use App\Services\Logic\Account\PhoneUpdateService;
use Illuminate\Http\Request;

class AccountController extends BaseController
{
    public $except = ['register', 'loginByPassword', 'loginByVerify'];

    /**
     * 注册
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $params = $request->all();
        $service = new AccountService();
        $token = $service->register($params);
        return $this->success(['token' => $token]);
    }

    /**
     * 密码登录
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function loginByPassword(Request $request)
    {
        $params = $request->all();
        $service = new AccountService();
        $token = $service->loginByPassword($params);
        return $this->success(['token' => $token]);
    }

    /**
     * 验证码登录
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function loginByVerify(Request $request)
    {
        $params = $request->all();
        $service = new AccountService();

        $token = $service->loginByVerify($params);

        return $this->success(['token' => $token]);
    }

    /**
     * 登出
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $service = new AccountService();

        $service->logout();

        return $this->success();
    }


    /**
     * 重置密码
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $params = $request->all();

        $service = new PasswordResetService();

        $service->handle($params);

        return $this->success();
    }


    /**
     * 修改手机号
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updatePhone(Request $request)
    {
        $params = $request->all();

        $service = new PhoneUpdateService();

        $service->handle($params);

        return $this->success();
    }


    /**
     * 修改邮箱
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updateEmail(Request $request)
    {
        $params = $request->all();

        $service = new EmailUpdateService();

        $service->handle($params);

        return $this->success();
    }


    /**
     * 修改密码
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $params = $request->all();

        $service = new PasswordUpdateService();

        $service->handle($params);

        return $this->success();
    }

}
