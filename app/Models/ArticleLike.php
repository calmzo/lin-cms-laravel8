<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleLike extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'article_id',
        'user_id',
    ];
}
