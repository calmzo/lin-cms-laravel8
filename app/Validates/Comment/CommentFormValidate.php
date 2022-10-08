<?php

namespace App\Validates\Answer;

use App\Validates\BaseValidate;
use Illuminate\Validation\Rule;


class CommentFormValidate extends BaseValidate
{


    protected function rule()
    {
        return [
            'question_id' => 'required|integer',
            'content' => 'required|between:10,30000',
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
