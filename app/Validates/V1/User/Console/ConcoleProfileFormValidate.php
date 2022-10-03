<?php

namespace App\Validates\V1\User\Console;

use App\Enums\UserEnums;
use App\Validates\BaseValidate;
use Illuminate\Validation\Rule;

class ConcoleProfileFormValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'name' => 'between:2,15',
            'gender' => [
                Rule::in(array_keys(UserEnums::genderTypes()))
            ],
            'area' => '',
            'about' => 'max:255',
            'avatar' => 'url',
        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
