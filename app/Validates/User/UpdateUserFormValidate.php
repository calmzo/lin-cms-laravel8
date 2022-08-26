<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class UpdateUserFormValidate extends BaseValidate
{
    protected $rule = [
        'username' => 'between:2,10',
        'email' => 'email',
        'nickname' => 'between:2,10',
    ];
}
