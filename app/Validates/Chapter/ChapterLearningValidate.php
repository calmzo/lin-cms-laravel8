<?php

namespace App\Validates\Chapter;

use App\Validates\BaseValidate;

class ChapterLearningValidate extends BaseValidate
{

    //验证规则
    protected function rule()
    {
        return [
            'request_id' => 'required|integer',
            'plan_id ' => '',
            'position ' => 'max:0,108000',
            'interval_time ' => '',
        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
