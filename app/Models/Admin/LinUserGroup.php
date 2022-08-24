<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Support\Str;

class LinUserGroup extends BaseModel
{
    /**
     * 表名约定
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Str::snake(class_basename($this));
    }

}
