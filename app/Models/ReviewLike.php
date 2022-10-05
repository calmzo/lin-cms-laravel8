<?php

namespace App\Models;

class ReviewLike extends BaseModel
{
    public $fillable = [
        'review_id',
        'user_id',
    ];
}
