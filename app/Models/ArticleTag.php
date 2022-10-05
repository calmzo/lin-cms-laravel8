<?php

namespace App\Models;

class ArticleTag extends BaseModel
{

    public $fillable = [
        'article_id',
        'tag_id',
    ];
}
