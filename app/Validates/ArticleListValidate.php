<?php

namespace App\Validates;

class ArticleListValidate extends BaseValidate
{
    protected $rule = [
        'page' => 'integer',
        'count' => 'integer',
        'start' => 'date',
        'end' => 'date',
    ];
}
