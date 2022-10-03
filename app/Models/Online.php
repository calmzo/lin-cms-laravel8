<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Online extends BaseModel
{
    use HasFactory, BooleanSoftDeletes;

    public $fillable = [
        'user_id', 'client_type', 'client_ip', 'active_time'
    ];
}
