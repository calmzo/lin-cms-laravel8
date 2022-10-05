<?php

namespace App\Models;

class ChapterLive extends BaseModel
{
    public $fillable = [];

    public function chapter()
    {
        return $this->hasOne(Chapter::class, 'id', 'chapter_id');
    }
}
