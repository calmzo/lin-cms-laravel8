<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionTag extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'question_id',
        'tag_id',
    ];
}
