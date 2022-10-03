<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionFavorite extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'question_id',
        'user_id',
    ];
}
