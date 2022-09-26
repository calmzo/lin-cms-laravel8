<?php

namespace App\Validates;


class TagSearchValidate extends BaseValidate
{
    protected $rule = [
        'page' => 'integer',
        'count' => 'integer',
        'start' => 'date',
        'end' => 'date',
    ];
}
