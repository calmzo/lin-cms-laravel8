<?php

namespace App\Validates;


class TagSearchValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'page' => 'integer',
            'count' => 'integer',
            'start' => 'date',
            'end' => 'date',
        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
