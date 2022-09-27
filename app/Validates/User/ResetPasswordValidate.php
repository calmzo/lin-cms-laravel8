<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class ResetPasswordValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'new_password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
