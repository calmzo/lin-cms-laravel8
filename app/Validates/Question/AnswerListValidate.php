<?php

namespace App\Validates\Question;

use App\Validates\BaseValidate;

class AnswerListValidate extends BaseValidate
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
