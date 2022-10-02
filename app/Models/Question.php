<?php

namespace App\Models;

use App\Caches\MaxQuestionId;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

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
