<?php

namespace App\Validates\Log;

use App\Validates\BaseValidate;

class LogListValidate extends BaseValidate
{

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
