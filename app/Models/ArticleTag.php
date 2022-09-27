<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleTag extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'article_id',
        'tag_id',
    ];
}
