<?php

namespace App\Validates;


class TagFormValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'required|between:2,30',
    ];

    //自定义验证信息
    protected $message = [


    ];

    //自定义场景
    protected $scene = [

    ];
}
