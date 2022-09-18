<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;
}
