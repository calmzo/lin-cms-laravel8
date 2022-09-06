<?php

namespace App\Services;

use App\Enums\ConnectEnums;
use App\Models\Connect as ConnectModel;
use App\Models\User as UserModel;
use App\Models\WechatSubscribe;
use App\Models\WeChatSubscribe as WeChatSubscribeModel;
use App\Repos\Connect as ConnectRepo;
use App\Repos\User as UserRepo;
use App\Repos\WeChatSubscribe as WeChatSubscribeRepo;
use App\Services\Auth\Home as AuthService;
use App\Services\Auth\Home as HomeAuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Services\Logic\Notice\AccountLogin as AccountLoginNotice;
use App\Lib\OAuth\QQ as QQAuth;
use App\Lib\OAuth\WeiBo as WeiBoAuth;
use App\Lib\OAuth\WeiXin as WeiXinAuth;
use App\Validators\Account as AccountValidator;

class ConnectService
{

    public function bindLogin()
    {
        $post = $this->request->getPost();

        $auth = $this->getConnectAuth($post['provider']);

        $auth->checkState($post['state']);

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $openUser = json_decode($post['open_user'], true);

        $this->handleConnectRelation($user, $openUser);

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);
    }

    public function bindRegister($params)
    {

        $auth = $this->getConnectAuth($params['provider']);

        $auth->checkState($params['state']);

        $openUser = json_decode($post['open_user'], true);

        $registerService = new RegisterService();

        $account = $registerService->handle();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $this->handleConnectRelation($user, $openUser);

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);
    }

    public function bindUser(array $openUser)
    {
        $user = $this->getLoginUser();

        $this->handleConnectRelation($user, $openUser);
    }

    public function authSubscribeLogin(WeChatSubscribeModel $subscribe)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($subscribe->user_id);

        $this->handleLoginNotice($user);

        $auth = new HomeAuthService();

        $auth->saveAuthInfo($user);
    }

    public function authConnectLogin(ConnectModel $connect)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($connect->user_id);

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);
    }

    public function getAuthorizeUrl($provider)
    {
        $auth = $this->getConnectAuth($provider);

        return $auth->getAuthorizeUrl();
    }

    public function getOpenUserInfo($code, $state, $provider)
    {
        $auth = $this->getConnectAuth($provider);

        $auth->checkState($state);

        $token = $auth->getAccessToken($code);

        $openId = $auth->getOpenId($token);

        return $auth->getUserInfo($token, $openId);
    }

    public function getWeChatSubscribe($unionId)
    {
        return WechatSubscribe::query()->where('union_id', $unionId)->first();
    }

    public function getConnectRelation($openId, $provider)
    {
        $connectRepo = new ConnectRepo();

        return $connectRepo->findByOpenId($openId, $provider);
    }

    public function getConnectAuth($provider)
    {
        $auth = null;

        switch ($provider) {
            case ConnectEnums::PROVIDER_QQ:
                $auth = $this->getQQAuth();
                break;
            case ConnectEnums::PROVIDER_WEIXIN:
                $auth = $this->getWeiXinAuth();
                break;
            case ConnectEnums::PROVIDER_WEIBO:
                $auth = $this->getWeiBoAuth();
                break;
        }


        if (!$auth) {
            throw new \Exception('Invalid OAuth Provider');
        }

        return $auth;
    }

    protected function getQQAuth()
    {
        $settings = config('oauth.qq');

        return new QQAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getWeiXinAuth()
    {
        $settings = config('oauth.weixin');

        return new WeiXinAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getWeiBoAuth()
    {
        $settings = config('oauth.weibo');

        return new WeiBoAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getAppAuth()
    {
        /**
         * @var $auth AuthService
         */
        $auth = $this->getDI()->get('auth');

        return $auth;
    }

    protected function handleConnectRelation(UserModel $user, array $openUser)
    {
        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByOpenId($openUser['id'], $openUser['provider']);

        if ($connect) {

            $connect->open_name = $openUser['name'];
            $connect->open_avatar = $openUser['avatar'];

            if ($connect->user_id != $user->id) {
                $connect->user_id = $user->id;
            }

            if (empty($connect->union_id) && !empty($openUser['unionid'])) {
                $connect->union_id = $openUser['unionid'];
            }

            if ($connect->deleted == 1) {
                $connect->deleted = 0;
            }

            $connect->update();

        } else {

            $connect = new ConnectModel();

            $connect->user_id = $user->id;
            $connect->union_id = $openUser['unionid'];
            $connect->open_id = $openUser['id'];
            $connect->open_name = $openUser['name'];
            $connect->open_avatar = $openUser['avatar'];
            $connect->provider = $openUser['provider'];

            $connect->create();
        }
    }

    protected function handleLoginNotice(UserModel $user)
    {
        $notice = new AccountLoginNotice();

        $notice->createTask($user);
    }

}
