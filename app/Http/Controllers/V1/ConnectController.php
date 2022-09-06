<?php

namespace App\Http\Controllers\V1;

use App\Enums\ConnectEnums;
use App\Services\ConnectService;
use Illuminate\Http\Request;

class ConnectController extends BaseController
{

    public function qqLogin()
    {
        $service = new ConnectService();

        $url = $service->getAuthorizeUrl(ConnectEnums::PROVIDER_QQ);

        return redirect()->to($url);
    }


    public function weixinLogin()
    {
        $service = new ConnectService();

        $url = $service->getAuthorizeUrl(ConnectEnums::PROVIDER_WEIXIN);

        return redirect()->to($url);
    }


    public function weiboLogin()
    {
        $service = new ConnectService();

        $url = $service->getAuthorizeUrl(ConnectEnums::PROVIDER_WEIBO);

        return redirect()->to($url);
    }

    /**
     * @Get("/qq/callback", name="home.oauth.qq_callback")
     */
    public function qqCallback()
    {
        $this->handleCallback(ConnectEnums::PROVIDER_QQ);
    }

    /**
     * @Get("/weixin/callback", name="home.oauth.weixin_callback")
     */
    public function weixinCallback()
    {
        $this->handleCallback(ConnectEnums::PROVIDER_WEIXIN);
    }

    /**
     * @Get("/weibo/callback", name="home.oauth.weibo_callback")
     */
    public function weiboCallback()
    {
        $this->handleCallback(ConnectEnums::PROVIDER_WEIBO);
    }

    /**
     * @Get("/weibo/refuse", name="home.oauth.weibo_refuse")
     */
    public function weiboRefuse()
    {
        return $this->response->redirect(['for' => 'home.account.login']);
    }


    public function bindLogin()
    {
        $service = new ConnectService();

        $service->bindLogin();

        $location = $this->url->get(['for' => 'home.uc.account']);
        return $this->success(['location' => $location]);
    }


    public function bindRegister(Request $request)
    {
        $params = $request->all();
        $service = new ConnectService();
        $service->bindRegister($params);

        $location = $this->url->get(['for' => 'home.uc.account']);
        return $this->success(['location' => $location]);
    }

    protected function handleCallback($provider)
    {
        $code = request()->get('code');
        $state = request()->get('state');
        $service = new ConnectService();

        $openUser = $service->getOpenUserInfo($code, $state, $provider);

        /**
         * 微信扫码登录检查是否关注过公众号，关注过直接登录
         */
        if ($provider == ConnectEnums::PROVIDER_WEIXIN && !empty($openUser['unionid'])) {
            $subscribe = $service->getWeChatSubscribe($openUser['unionid']);
            if ($subscribe && is_null($subscribe->delete_time)) {
                $service->authSubscribeLogin($subscribe);
                return redirect()->to(['for' => 'home.index']);
            }
        }

        $connect = $service->getConnectRelation($openUser['id'], $openUser['provider']);

        if ($this->authUser->id > 0) {
            if ($openUser) {
                $service->bindUser($openUser);
                return redirect()->to(['for' => 'home.uc.account']);
            }
        } else {
            if ($connect) {
                $service->authConnectLogin($connect);
                return redirect()->to(['for' => 'home.index']);
            }
        }

        $captcha = $service->getSettings('captcha');

        $this->view->pick('connect/bind');
        $this->view->setVar('captcha', $captcha);
        $this->view->setVar('provider', $provider);
        $this->view->setVar('open_user', $openUser);
    }
}
