<?php

namespace App\Services\Token;

use Illuminate\Support\Facades\Auth;

class AccountLoginTokenService
{


    public static function user()
    {
        return Auth::guard('api')->user();
    }

    public static function payload()
    {
        return Auth::guard('api')->getPayload();
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
        return Auth::guard('api')->user()->getAuthIdentifier();
    }

    /**
     * @param $user
     */
    public static function getToken($user)
    {
        return Auth::guard('api')->login($user);
    }

    public static function logout()
    {
        Auth::guard('api')->logout();
    }

}
