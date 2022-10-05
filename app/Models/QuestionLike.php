<?php

namespace App\Models;

class QuestionLike extends BaseModel
{
    public $fillable = [
        'question_id',
        'user_id'
    ];
}
