<?php

namespace App\Models\Admin;

use think\Model;
use think\model\concern\SoftDelete;

class LinFile extends Model
{
    use SoftDelete;

    public $autoWriteTimestamp = 'datetime';
}
