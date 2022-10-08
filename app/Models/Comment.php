<?php

namespace App\Models;

class Comment extends BaseModel
{
    public $fillable = [
        'item_id', 'item_type', 'user_id', 'published', 'client_type', 'client_ip', 'content'
    ];

}
