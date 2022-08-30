<?php

namespace App\Models;

use App\Traits\ClosureTable;
use Illuminate\Database\Eloquent\Builder;

class OrganizationRelation extends BaseModel
{
    use ClosureTable;
    protected $guarded = [];

    public static function master(): Builder
    {
        return Organization::query();
    }

    public static function tableName()
    {
        return 'organization_relation';
    }
}
