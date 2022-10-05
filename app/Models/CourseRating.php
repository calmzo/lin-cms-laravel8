<?php

namespace App\Models;

class CourseRating extends BaseModel
{

    protected $primaryKey = 'course_id';

    public $fillable = [
        'course_id',
        'rating',
        'rating1',
        'rating2',
        'rating3',
    ];
}
