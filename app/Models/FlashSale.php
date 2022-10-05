<?php

namespace App\Models;

class FlashSale extends BaseModel
{
    public $fillable = [];

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

    public function setSchedulesAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['schedules'] = $value;
    }

    public function getSchedulesAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }
}
