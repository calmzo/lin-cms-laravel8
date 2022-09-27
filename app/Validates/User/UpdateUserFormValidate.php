<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class UpdateUserFormValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'username' => 'between:2,10',
            'email' => 'email',
            'nickname' => 'between:2,10',
        ];
    }


    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
