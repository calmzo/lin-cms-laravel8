<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;
}
