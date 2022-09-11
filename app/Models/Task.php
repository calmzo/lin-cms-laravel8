<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'item_id');
    }

    public function trade()
    {
        return $this->hasOne(Trade::class, 'id', 'item_id');
    }

    public function refund()
    {
        return $this->hasOne(Refund::class, 'id', 'item_id');
    }

    public function setItemInfoAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['item_info'] = $value;
    }

    public function getItemInfoAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;

    }
}
