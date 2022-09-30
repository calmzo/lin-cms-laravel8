<?php

namespace App\Models;

use App\Caches\MaxArticleIdCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'published', 'user_id', 'title', 'content', 'source_type', 'closed', 'private', 'tags', 'client_ip', 'client_type', 'source_url', 'category_id', 'cover', 'summary', 'word_count'
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    protected static function booted()
    {
        //处理 Article「created」事件
        static::created(function () {
            $cache = new MaxArticleIdCache();
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
