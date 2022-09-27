<?php

namespace App\Validates;


class ArticleSearchValidate extends BaseValidate
{
    protected $rule = [
        'page' => 'integer',
        'count' => 'integer',
        'start' => 'date',
        'end' => 'date',
    ];
}
