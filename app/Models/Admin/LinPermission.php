<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use App\Models\BooleanSoftDeletes;
use think\model\concern\SoftDelete;

class LinPermission extends BaseModel
{
    use BooleanSoftDeletes;

    public $fillable = [
        'name', 'module', 'mount'
    ];

}
