<?php

namespace App\Models;

class PointHistory extends BaseModel
{

    public function setEventInfoAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['event_info'] = $value;
    }

    public function getEventInfoAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }
}
