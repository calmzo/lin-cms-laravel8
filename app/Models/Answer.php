<?php

namespace App\Models;

use App\Caches\MaxAnswerIdCache;

class Answer extends BaseModel
{
    public $fillable = [
        'client_type', 'user_id', 'question_id', 'cover', 'summary', 'content', 'anonymous',
        'accepted', 'published', 'client_ip', 'comment_count', 'like_count', 'report_count'
    ];

    protected static function booted()
    {
        //处理 Article「created」事件
        static::created(function () {
            $cache = new MaxAnswerIdCache();
            $cache->rebuild();
        });

    }
}
