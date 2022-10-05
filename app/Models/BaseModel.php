<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasFactory, BooleanSoftDeletes;

    public const CREATED_AT = 'create_time';
    public const UPDATED_AT = 'update_time';
    public const DELETED_AT = 'deleted';

//    protected $hidden = [
//        'create_time', 'update_time', 'delete_time'
//    ];

    protected $hidden = [
        'deleted'
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
//    public $defaultCasts = ['deleted' => 'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
//        parent::mergeCasts($this->defaultCasts);
    }

    /**
     * 类初始化
     * @return $this
     */
    public function new()
    {
        return new static();
    }

    public function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->toDateTimeString();
    }


}
