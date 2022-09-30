<?php

namespace App\Validates\Report;

use App\Enums\CommentEnums;
use App\Validates\BaseValidate;
use Illuminate\Validation\Rule;

class ReportCommentListValidate extends BaseValidate
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
            'item_id' => [
                'integer',
                Rule::in(array_keys(CommentEnums::itemTypes()))
            ],
            'item_type' => 'integer',
            'parent_id' => 'integer',
            'published' => '',

        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
