<?php

namespace App\Validates\Live;

use App\Validates\BaseValidate;

class LiveListValidate extends BaseValidate
{

    protected function rule()
    {
        return [
            'page' => 'integer',
            'count' => 'integer',
            'start' => 'date',
            'end' => 'date',
            'id' => 'integer',
            'user_id' => 'integer',
            'question_id' => 'integer',
            'published' => '',

        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
