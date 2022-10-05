<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseRating extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    protected $primaryKey = 'course_id';

    public $fillable = [
        'course_id',
        'rating',
        'rating1',
        'rating2',
        'rating3',
    ];
}
