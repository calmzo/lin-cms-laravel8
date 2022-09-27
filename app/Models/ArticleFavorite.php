<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleFavorite extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'article_id',
        'user_id',
    ];
}
