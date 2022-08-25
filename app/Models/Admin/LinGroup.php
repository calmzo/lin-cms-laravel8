<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

class LinGroup extends BaseModel
{

    protected $table = 'lin_group';

    public $fillable = [
        'name', 'info'
    ];

    public function users()
    {
        return $this->belongsToMany(LinUser::class, 'Lin_user_group', 'group_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(LinPermission::class, 'lin_group_permission', 'group_id', 'permission_id');
    }
}
