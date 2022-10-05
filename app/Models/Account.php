<?php

namespace App\Models;


class Account extends BaseModel
{

    public $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];
}
