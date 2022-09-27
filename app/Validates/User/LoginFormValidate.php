<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

/**
 * 登录验证器
 */
class LoginFormValidate extends BaseValidate {

    //验证规则
    protected function rule()
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }


    //自定义验证信息
    protected function message()
    {
        return [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
        ];
    }

    //自定义场景
    protected $scene = [

    ];

}
