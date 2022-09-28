<?php

namespace App\Validates;

use App\Enums\ArticleEnums;
use App\Enums\ReasonEnums;
use App\Enums\ReportEnums;
use Illuminate\Validation\Rule;


class ReportFormValidate extends BaseValidate
{


    protected function rule()
    {
        return [
            'item_id' => 'required|integer',
            'item_type' => [
                'required',
                'integer',
                Rule::in(array_keys(ReportEnums::itemTypes()))
            ],
            'reason' => [
                Rule::in(array_keys(ReasonEnums::reportOptions()))
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
