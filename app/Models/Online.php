<?php

namespace App\Models;

class Online extends BaseModel
{
    public $fillable = [
        'user_id', 'client_type', 'client_ip', 'active_time'
    ];
}
