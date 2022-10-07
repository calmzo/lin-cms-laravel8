<?php

namespace App\Models;

class ChapterLike extends BaseModel
{
    public $fillable = [
        'chapter_id', 'user_id'
    ];
}
