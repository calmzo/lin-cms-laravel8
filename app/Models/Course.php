<?php

namespace App\Models;

use App\Caches\MaxCourseIdCache;
use App\Enums\CourseEnums;
use App\Services\Sync\CourseIndexSync;
use App\Services\Sync\CourseScoreSync;

class Course extends BaseModel
{

    public $fillable = [

    ];

    /**
     * @var array
     *
     * 点播扩展属性
     */
    protected $_vod_attrs = [
        'duration' => 0,
    ];

    /**
     * @var array
     *
     * 直播扩展属性
     */
    protected $_live_attrs = [
        'start_date' => '',
        'end_date' => '',
    ];

    /**
     * @var array
     *
     * 图文扩展属性
     */
    protected $_read_attrs = [
        'duration' => 0,
        'word_count' => 0,
    ];

    /**
     * @var array
     *
     * 面授扩展属性
     */
    protected $_offline_attrs = [
        'start_date' => '',
        'end_date' => '',
        'user_limit' => 30,
        'location' => '',
    ];


    protected static function booted()
    {
        static::creating(function ($course) {
            if (empty($course->attrs)) {
                if ($course->model == CourseEnums::MODEL_VOD) {
                    $course->attrs = $this->_vod_attrs;
                } elseif ($course->model == CourseEnums::MODEL_LIVE) {
                    $course->attrs = $this->_live_attrs;
                } elseif ($course->model == CourseEnums::MODEL_READ) {
                    $course->attrs = $this->_read_attrs;
                } elseif ($course->model == CourseEnums::MODEL_OFFLINE) {
                    $course->attrs = $this->_offline_attrs;
                }
            }
        });

        //处理 Course「created」事件
        static::created(function ($course) {
            $cache = new MaxCourseIdCache();
            $cache->rebuild();
            $res = CourseRating::query()->create(['course_id' => $course->id]);
            if (!$res) {
                throw new \RuntimeException('Create Course Rating Failed');
            }
        });

        static::updating(function ($course) {
            if (time() - strtotime($course->update_time) > 3 * 3600) {
                $sync = new CourseIndexSync();
                $sync->addItem($course->id);

                $sync = new CourseScoreSync();
                $sync->addItem($course->id);
            }
        });

        static::saving(function ($course) {
            if (empty($course->cover)) {
                $course->cover = kg_default_course_cover_path();
            }
            if (empty($course->summary)) {
                $course->summary = kg_parse_summary($course->details);
            }
            if (empty($course->origin_price)) {
                $course->origin_price = 1.5 * $course->market_price;
            }
        });


    }

    public function setAttrsAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['attrs'] = $value;
    }

    public function getAttrsAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;
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
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'course_package', 'course_id', 'package_id');
    }


}
