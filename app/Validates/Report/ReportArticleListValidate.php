<?php

namespace App\Validates\Report;

use App\Validates\BaseValidate;

class ReportArticleListValidate extends BaseValidate
{

    protected function rule()
    {
        return [
            'page' => 'integer',
            'count' => 'integer',
            'start' => 'date',
            'end' => 'date',
            'tag_id' => 'integer',
            'id' => 'integer',
            'category_id' => 'integer',
            'user_id' => 'integer',
            'source_type' => 'integer',
            'title' => '',
            'private' => '',
            'featured' => '',
            'published' => '',
            'closed' => '',
        ];
    }

    //自定义验证信息
    protected function message()
    {
        return [];
    }
}
