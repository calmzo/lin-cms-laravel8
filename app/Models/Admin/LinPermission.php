<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use think\model\concern\SoftDelete;

class LinPermission extends BaseModel
{

    public $fillable = [
        'name', 'module', 'mount'
    ];

}
