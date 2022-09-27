<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class ChangePasswordFormValidate extends BaseValidate
{

    protected function rule()
    {
        return [
            'old_password' => 'required',
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
