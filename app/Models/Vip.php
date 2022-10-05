<?php

namespace App\Models;

class Vip extends BaseModel
{
    public $fillable = [
        'title', 'cover', 'expiry', 'price'
    ];
}
