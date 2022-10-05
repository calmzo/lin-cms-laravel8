<?php

namespace App\Models;

class ArticleLike extends BaseModel
{
    public $fillable = [
        'article_id',
        'user_id',
    ];
}
