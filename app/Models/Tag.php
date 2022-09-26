<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    protected $fillable = ['id', 'name', 'alias'];
}
