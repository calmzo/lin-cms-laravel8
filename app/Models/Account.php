<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;
}
