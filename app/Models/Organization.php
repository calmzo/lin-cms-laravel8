<?php

namespace App\Models;


class Organization extends BaseModel
{
    use BooleanSoftDeletes;
    protected $table = 'organization';


    public $fillable = [
        'name',
        'pid',
        'company_id',
        'sort',
    ];

    // 关联所有父级
    public function parents()
    {
        return $this->belongsToMany(Organization::class, OrganizationRelation::class, 'node_id', 'root_id')->where('depth', '<>', 0);
    }

    // 关联所有子级
    public function childrens()
    {
        return $this->belongsToMany(Organization::class, OrganizationRelation::class, 'root_id', 'node_id')->where('depth', '<>', 0);
    }


    protected static function boot()
    {
        parent::boot();
        static::created(function (Organization $organization) {
            OrganizationRelation::insert($organization->id, $organization->pid);
        });
        static::updated(function (Organization $organization) {
            $old_pid = $organization->getOriginal('pid');
            // 更新移动节点关系
            if ((int)$old_pid != (int)$organization->pid) {
                OrganizationRelation::move($organization->id, $organization->pid);
            }
        });
        static::deleted(function (Organization $organization) {
            // 获取所有子部门
            $children_ids = OrganizationRelation::childrenNodeId($organization->id);
            // 删除所有子部门
            Organization::query()->whereIn('id', $children_ids->toArray())->delete();
            // 移除节点关联关系
            OrganizationRelation::remove($organization->id);
        });
    }
}
