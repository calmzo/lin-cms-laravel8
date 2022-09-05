<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [

    ];
}
