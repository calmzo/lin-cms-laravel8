<?php

namespace App\Validates;

use App\Enums\ArticleEnums;
use Illuminate\Validation\Rule;


class ArticleFormValidate extends BaseValidate
{


    protected function rule()
    {
        return [
            'title' => 'required|between:2,50',
            'content' => 'required|between:10,30000',
            'category_id' => 'integer',
            'source_type' => [
                Rule::in(array_keys(ArticleEnums::sourceTypes()))
            ],
            'source_url' => 'url',
            'closed' => [
                Rule::in([0, 1]),
            ],
            'private' => [
                Rule::in([0, 1]),
            ],
            'xm_tag_ids' => '',
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
