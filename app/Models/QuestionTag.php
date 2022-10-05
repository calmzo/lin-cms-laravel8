<?php

namespace App\Models;

class QuestionTag extends BaseModel
{
    public $fillable = [
        'question_id',
        'tag_id',
    ];
}
