<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use App\Models\BooleanSoftDeletes;

class LinFile extends BaseModel
{
    use BooleanSoftDeletes;

    public $fillable = [
        'name',
        'path',
        'size',
        'extension',
        'md5',
        'type'
    ];
}
