<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;
    public $fillable = [
        'client_type', 'client_ip', 'course_id', 'user_id', 'content', 'rating1', 'rating2', 'rating3', 'published'
    ];
}


