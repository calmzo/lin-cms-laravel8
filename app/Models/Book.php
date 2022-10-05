<?php

namespace App\Models;

class Book extends BaseModel
{
    public $fillable = [
        'title',
        'author',
        'summary',
        'image',
    ];
}
