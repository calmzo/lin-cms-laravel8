<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasFactory;

    public const CREATED_AT = 'create_time';
    public const UPDATED_AT = 'update_time';
    public const DELETED_AT = 'delete_time';

    protected $hidden = [
       'create_time',  'update_time', 'delete_time'
    ];
    /**
     * 表名约定
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Str::snake(class_basename($this));
    }

    /**
     * 数据转换
     * @var string[]
     */
    public $defaultCasts = ['deleted' => 'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        parent::mergeCasts($this->defaultCasts);
    }

    /**
     * 类初始化
     * @return $this
     */
    public function new()
    {
        return new static();
    }

    /**
     * 转驼峰
     * @return array
     */
//    public function toArray()
//    {
//        $items = parent::toArray();
//        $keys = array_keys($items);
//        $keys = array_map(function ($item) {
//            return lcfirst(Str::studly($item));
//        }, $keys);
//        $values = array_values($items);
//        return array_combine($keys, $values);
//    }

    public function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->toDateTimeString();
    }


}
