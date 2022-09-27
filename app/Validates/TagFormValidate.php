<?php

namespace App\Validates;


class TagFormValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'name' => 'required|between:2,30',
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
