<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vip extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'title', 'cover', 'expiry', 'price'
    ];
}
