<?php

namespace App\Services\Token;

use App\Models\Admin\LinUser;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Builder;

class LoginTokenService
{
    public static function user(): array
    {
        $payload = self::payload()->toArray();
        $user = Auth::guard('cms')->user()->toArray();
        $user['admin'] = $payload['admin'];
        $user['permission'] = $payload['permission'];
        return $user;
    }

    public static function payload()
    {
        return Auth::guard('cms')->getPayload();
    }


    public static function isLogin()
    {
        return !is_null(self::user());
    }

    /**
     * @return mixed
     */
    public static function userId()
    {
        return self::user()->getAuthIdentifier();
    }

    /**
     * @param $user
     * @return array
     */
    public static function getToken($user)
    {
        $userPermissions = UserService::getPermissions($user->id);
        $claims['permission'] = $userPermissions['permissions'];
        $claims['admin'] = $userPermissions['admin'];
        $accessToken = Auth::guard('cms')->claims($claims)->login($user);
//        $refreshToken = Auth::guard('cms')->refresh();
        $token = [
            'accessToken' => $accessToken,
//            'refreshToken' => $refreshToken,
        ];
        return $token;
    }
}
