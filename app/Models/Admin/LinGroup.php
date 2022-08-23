<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

class LinGroup extends BaseModel
{

    protected $table = 'lin_group';


    public function users()
    {
        return $this->belongsToMany('LinUserModel', 'Lin_user_group', 'user_id', 'group_id');
    }

    public function permissions()
    {
        return $this->belongsToMany('LinPermissionModel', 'lin_group_permission', 'permission_id', 'group_id');
    }
}
