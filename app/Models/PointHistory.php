<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointHistory extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;


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
