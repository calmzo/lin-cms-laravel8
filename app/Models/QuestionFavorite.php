<?php

namespace App\Models;

class QuestionFavorite extends BaseModel
{
    public $fillable = [
        'question_id',
        'user_id',
    ];
}
