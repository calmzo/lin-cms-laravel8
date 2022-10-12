<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class RefundStatus extends Model
{
    public const CREATED_AT = 'create_time';

    /**
     * 表名约定
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Str::snake(class_basename($this));
    }

    public $fillable = [
        'refund_id', 'status'
    ];

}
