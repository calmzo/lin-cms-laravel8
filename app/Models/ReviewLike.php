<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewLike extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'review_id',
        'user_id',
    ];
}
