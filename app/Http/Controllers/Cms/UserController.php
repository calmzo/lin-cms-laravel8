<?php

namespace App\Http\Controllers\Cms;

//use app\api\validate\user\LoginForm;  # 开启注释验证器以后，本行可以去掉，这里做更替说明
//use app\api\validate\user\RegisterForm; # 开启注释验证器以后，本行可以去掉，这里做更替说明
use app\api\service\token\LoginToken;
use App\Services\Admin\UserService;
use App\Services\Token\LoginTokenService;
use App\Utils\CodeResponse;
use Illuminate\Http\Request;


class UserController extends BaseController
{

    protected $except = ['userLogin', 'register'];

    /**
     * @var LoginToken
     */
//    private $loginTokenService;

//    /**
//     * User constructor.
//     */
//    public function __construct()
//    {
//        $this->loginTokenService = LoginToken::getInstance();
//    }


    /**
     * @adminRequired
     * @permission('注册','管理员','hidden')
     * @param Request $request
     * @validate('RegisterForm')
     * @return Json
     * @throws NotFoundException
     * @throws OperationException
     * @throws RepeatException
     * @throws ForbiddenException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function register(Request $request)
    {
        $params = $request->all();
        $user = UserService::createUser($params);
//        Hook::listen('logger', "新建了用户：{$user['username']}");
        return $this->success($user['id'], '注册用户成功');
    }

    /**
     * @param Request $request
     * @validate('LoginForm')
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws NotFoundException
     * @throws AuthFailedException
     */
    public function userLogin(Request $request)
    {
        $username = $request->input('username', '');
        $password = $request->input('password', '');
        $user = UserService::verify($username, $password);
        $token = LoginTokenService::getToken($user);
//        Hook::listen('logger', array('uid' => $user->id, 'username' => $user->identifier, 'msg' => '登陆成功获取了令牌'));
        return [
            'access_token' => $token['accessToken'],
            'refresh_token' => $token['refreshToken']
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
     * @loginRequired
     */
    public function getAllowedApis()
    {
        $uid = $this->loginTokenService->getCurrentUid();
        return UserService::getPermissions($uid);
    }

    /**
     * @loginRequired
     * @return mixed
     */
    public function getInformation()
    {
        $uid = $this->loginTokenService->getCurrentUid();
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
        $params = $request->put();
        $row = UserService::updateUser($params);
        return writeJson(200, $row, '用户信息更新成功');
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
        $oldPassword = $request->put('old_password');
        $newPassword = $request->put('new_password');

        $row = UserService::changePassword($oldPassword, $newPassword);

        Hook::listen('logger', '修改了自己的密码');
        return writeJson(200, $row, '密码修改成功');
    }
}
