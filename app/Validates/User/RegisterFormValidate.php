<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class RegisterFormValidate extends BaseValidate
{
    protected $rule = [
        'password' => 'required|same:confirm_password',
        'confirm_password' => 'required',
        'username' => 'required|between:2,10',
        'group_ids' => 'array',
        'email' => 'email'
    ];

    //自定义验证信息
    protected $message = [


    ];

    //自定义场景
    protected $scene = [

    ];
}
