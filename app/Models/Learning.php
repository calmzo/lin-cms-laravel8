<?php

namespace App\Models;

class Learning extends BaseModel
{
    public $fillable = [
        'course_id', 'request_id', 'chapter_id', 'user_id', 'plan_id', 'duration', 'position', 'client_type', 'client_ip', 'active_time'
    ];
}
