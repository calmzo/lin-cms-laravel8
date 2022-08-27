<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'title',
        'author',
        'summary',
        'image',
    ];
}
