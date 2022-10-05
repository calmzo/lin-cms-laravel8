<?php

namespace App\Models;

class CourseFavorite extends BaseModel
{

    public $fillable = [
        'course_id',
        'user_id',
    ];
}
