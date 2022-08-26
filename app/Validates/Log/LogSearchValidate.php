<?php

namespace App\Validates\Log;

use App\Validates\BaseValidate;

class LogSearchValidate extends BaseValidate
{
    protected $rule = [
        'page' => 'integer',
        'count' => 'integer',
        'start' => 'date',
        'end' => 'date',
    ];
}
