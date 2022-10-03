<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseFavorite extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'course_id',
        'user_id',
    ];
}
