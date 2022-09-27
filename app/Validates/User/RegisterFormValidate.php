<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class RegisterFormValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
            'username' => 'required|between:2,10',
            'group_ids' => 'array',
            'email' => 'email'
        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
    //自定义场景
    protected $scene = [

    ];
}
