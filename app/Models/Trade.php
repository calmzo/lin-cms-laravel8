<?php

namespace App\Models;

class Trade extends BaseModel
{
    public $fillable = [
        'subject', 'amount', 'channel', 'order_id', 'user_id', 'order_id', 'sn'
    ];
}
