<?php

namespace App\Models;

use App\Caches\MaxQuestionId;

class Question extends BaseModel
{
    protected static function booted()
    {
        //处理 Question「created」事件
        static::created(function () {
            $cache = new MaxQuestionId();
            $cache->rebuild();
        });

    }

    public function setTagsAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['tags'] = $value;
    }

    public function getTagsAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }
}
