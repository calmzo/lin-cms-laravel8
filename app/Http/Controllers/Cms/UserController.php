<?php

namespace App\Http\Controllers\Cms;

use App\Validates\User\RegisterFormValidate;
use App\Events\Logger;
use App\Validates\User\LoginFormValidate;
use App\Services\Admin\UserService;
use App\Services\Token\LoginTokenService;
use Illuminate\Http\Request;


class UserController extends BaseController
{

    protected $except = ['login', 'register'];

    /**
     * 注册
     * @adminRequired
     * @permission('注册','管理员','hidden')
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\OperationException
     * @throws \App\Exceptions\RepeatException
     */
    public function register(RegisterFormValidate $registerFormValidate)
    {
        $params = $registerFormValidate->check();
        $user = UserService::createUser($params);
        Logger::dispatch("新建了用户：{$user['username']}");
        return $this->success($user['id'], '注册用户成功');
    }

    /**
     * 用户登录
     * @param LoginFormValidate $loginFormValidate
     * @return array
     * @throws \App\Exceptions\AuthFailedException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\ParameterException
     */
    public function login(LoginFormValidate $loginFormValidate)
    {
        $params = $loginFormValidate->check();
        $username = $params['username'];
        $password = $params['password'];
        $user = UserService::verify($username, $password);
        $token = LoginTokenService::getToken($user);
        Logger::dispatch(array('uid' => $user->id, 'username' => $user->username, 'msg' => '登陆成功获取了令牌'));
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
     * @throws \App\Exceptions\UserException
     */
    public function getAllowedApis()
    {
        $uid = LoginTokenService::userId();
        return UserService::getPermissions($uid);
    }

    /**
     * @loginRequired
     * @return mixed
     */
    public function getInformation()
    {
        $uid = LoginTokenService::userId();
        return UserService::getInformation($uid);
    }

    /**
     * @loginRequired
     * @param Request $request
     * @validate('UpdateUserForm')
     * @return Json
     * @throws RepeatException
     */
    public function update(Request $request)
    {
        $params = $request->all();
        $row = UserService::updateUser($params);
        return $this->success([], '用户信息更新成功');
    }

    /**
     * @loginRequired
     * @validate('ChangePasswordForm')
     * @param Request $request
     * @return Json
     * @throws AuthFailedException
     * @throws NotFoundException
     */
    public function changePassword(Request $request)
    {
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');

        $row = UserService::changePassword($oldPassword, $newPassword);
        Logger::dispatch("修改了自己的密码");
        return $this->success([], '密码修改成功');
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
