<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;
}
