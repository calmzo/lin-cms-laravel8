<?php

namespace App\Models;

class AnswerLike extends BaseModel
{
    public $fillable = [
        'answer_id',
        'user_id',
    ];
}
