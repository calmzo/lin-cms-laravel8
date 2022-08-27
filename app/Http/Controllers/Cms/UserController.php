<?php

namespace App\Http\Controllers\Cms;

use App\Validates\User\ChangePasswordFormValidate;
use App\Validates\User\RegisterFormValidate;
use App\Events\Logger;
use App\Validates\User\LoginFormValidate;
use App\Services\Admin\UserService;
use App\Services\Token\LoginTokenService;
use App\Validates\User\UpdateUserFormValidate;
use Illuminate\Http\Request;


class UserController extends BaseController
{

    protected $except = ['login', 'register'];

    /**
     * @adminRequired
     * @permission('注册','管理员','hidden')
     * @param RegisterFormValidate $registerFormValidate
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Token\ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\OperationException
     * @throws \App\Exceptions\RepeatException
     * @throws \App\Exceptions\ValidateException
     */
    public function register(RegisterFormValidate $registerFormValidate)
    {
        $params = $registerFormValidate->check();
        $user = UserService::createUser($params);
        Logger::dispatch("新建了用户：{$user['username']}");
        return $this->success($user['id'], '注册用户成功');
    }

    /**
     * @param LoginFormValidate $loginFormValidate
     * @return array
     * @throws \App\Exceptions\AuthFailedException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\ValidateException
     */
    public function login(LoginFormValidate $loginFormValidate)
    {
        $params = $loginFormValidate->check();
        $username = $params['username'];
        $password = $params['password'];
        $tokenExtend = UserService::verify($username, $password);
        $token = LoginTokenService::getToken($tokenExtend);
        Logger::dispatch(array('uid' => $tokenExtend['id'], 'username' => $tokenExtend['username'], 'msg' => '登陆成功获取了令牌'));
        return [
            'access_token' => $token['accessToken'],
            'refresh_token' => $token['refreshToken'] ?? ''
        ];
    }

    /**
     * @return array
     * @throws TokenException
     */
    public function refreshToken()
    {
        $token = $this->loginTokenService->getTokenFromHeaders();
        $token = $this->loginTokenService->refresh($token);
        return [
            'access_token' => $token['accessToken']
        ];
    }

    /**
     * 查询自己拥有的权限
     * @return array
     */
    public function getAllowedApis()
    {
        $uid = LoginTokenService::userId();
        return UserService::getPermissions($uid);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInformation()
    {
        $uid = LoginTokenService::userId();
        return UserService::getInformation($uid);
    }

    /**
     * @param UpdateUserFormValidate $updateUserFormValidate
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\RepeatException
     * @throws \App\Exceptions\ValidateException
     */
    public function update(UpdateUserFormValidate $updateUserFormValidate)
    {
        $params = $updateUserFormValidate->check();
        $row = UserService::updateUser($params);
        return $this->success($row, '用户信息更新成功');
    }

    /**
     * @param ChangePasswordFormValidate $changePasswordFormValidate
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AuthFailedException
     * @throws \App\Exceptions\ValidateException
     */
    public function changePassword(ChangePasswordFormValidate $changePasswordFormValidate)
    {
        $params = $changePasswordFormValidate->check();
        $oldPassword = $params['old_password'];
        $newPassword = $params['new_password'];
        $row = UserService::changePassword($oldPassword, $newPassword);
        Logger::dispatch("修改了自己的密码");
        return $this->success($row, '密码修改成功');
    }

    /**
     * 更新头像
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function setAvatar(Request $request)
    {
        $url = $request->input('avatar');
        $uid = LoginTokenService::userId();
        UserService::updateUserAvatar($uid, $url);

        return $this->success([], '更新头像成功');
    }
}
