<?php

namespace App\Validates\V1\User;

use App\Validates\BaseValidate;

class QuestionListValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'page' => 'integer',
            'limit' => 'integer',
            'sort ' => '',
        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
