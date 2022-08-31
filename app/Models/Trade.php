<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trade extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'subject', 'amount', 'channel', 'order_id', 'user_id', 'order_id', 'sn'
    ];
}
