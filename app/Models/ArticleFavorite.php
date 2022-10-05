<?php

namespace App\Models;

class ArticleFavorite extends BaseModel
{

    public $fillable = [
        'article_id',
        'user_id',
    ];
}
