<?php

namespace App\Validates\User;

use App\Validates\BaseValidate;

class ResetPasswordValidate extends BaseValidate
{
    protected $rule = [
        'new_password' => 'required|same:confirm_password',
        'confirm_password' => 'required',
    ];
}
