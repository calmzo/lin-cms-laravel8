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
        $user = Auth::guard('admin')->user()->toArray();
        $payload = self::payload();
        $user['admin'] = $payload['admin'];
        $user['permissions'] = $payload['permissions'];
        return $user;
    }

    public static function payload(): array
    {
        return Auth::guard('admin')->getPayload()->toArray();
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
        return Auth::guard('admin')->user()->getAuthIdentifier();
    }

    /**
     * @param $user
     * @return array
     */
    public static function getToken($user)
    {
        $userPermissions = UserService::getPermissions($user->id);
        $claims['permissions'] = $userPermissions['permissions'];
        $claims['admin'] = $userPermissions['admin'];
        $accessToken = Auth::guard('admin')->claims($claims)->login($user);
//        $refreshToken = Auth::guard('cms')->refresh();
        $token = [
            'accessToken' => $accessToken,
//            'refreshToken' => $refreshToken,
        ];
        return $token;
    }
}
