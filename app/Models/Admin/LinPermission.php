<?php

namespace App\Models\Admin;

use think\Model;
use think\model\concern\SoftDelete;

class LinPermission extends Model
{
    use SoftDelete;

    public $autoWriteTimestamp = 'datetime';
    public $hidden = ['create_time', 'update_time', 'delete_time'];
}
