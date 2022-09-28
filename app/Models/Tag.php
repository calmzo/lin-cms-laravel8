<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    protected $fillable = ['id', 'name', 'alias', 'scopes'];

    public function setScopesAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['scopes'] = $value;
    }

    public function getScopesAttribute($value)
    {
        if (is_string($value) && $value != 'all') {
            $value = json_decode($value, true);
        }
        return $value;
    }

}
