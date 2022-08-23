<?php

namespace App\Services\Token;

use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Builder;

class LoginTokenService
{

    /**
     * @param null $uid
     * @return string
     * 生成token
     */
    public static function createToken($uid = null)
    {
        $signer = new Sha256();//加密规则
        $time = time();//当前时间

        $token = (new Builder())
            ->issuedBy('teacher')//签发人
            ->identifiedBy('MarsLei', true) //标题id
            ->issuedAt($time)//发出令牌的时间
            ->canOnlyBeUsedAfter($time) //生效时间(即时生效)
            ->expiresAt($time + 3600) //过期时间
            ->with('uid', $uid) //用户id
            ->sign($signer, 'my') //签名
            ->getToken(); //得到token
        return (string)$token;
    }





    public function user()
    {
        return Auth::guard('cms')->user();
    }


    public function isLogin()
    {
        return !is_null($this->user());
    }

    public function userId()
    {
        return $this->user()->getAuthIdentifier();
    }

    public function getToken($user)
    {
        $accessToken = Auth::guard('cms')->login($user);
        $refreshToken = Auth::guard('cms')->refresh();
        $token = [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
        ];
        return $token;
    }
}
