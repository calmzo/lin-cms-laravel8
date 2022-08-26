<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class ChangePasswordFormValidate extends BaseValidate
{
    protected $rule = [
        'old_password' => 'required',
        'new_password' => 'required|same:confirm_password',
        'confirm_password' => 'required',
    ];
}
