<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

class LinFile extends BaseModel
{
    public $fillable = [
        'name',
        'path',
        'size',
        'extension',
        'md5',
        'type'
    ];
}
