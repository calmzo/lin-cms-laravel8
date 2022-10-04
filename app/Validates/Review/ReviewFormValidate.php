<?php

namespace App\Validates\Review;

use App\Validates\BaseValidate;
use Illuminate\Validation\Rule;


class ReviewFormValidate extends BaseValidate
{


    protected function rule()
    {
        return [
            'course_id' => 'integer',
            'content' => 'required|between:10,255',
            'rating1' => [
                Rule::in([1,2,3,4,5])
            ],
            'rating2' => [
                Rule::in([1,2,3,4,5])
            ],
            'rating3' => [
                Rule::in([1,2,3,4,5])
            ],
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
