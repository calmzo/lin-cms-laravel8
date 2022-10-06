<?php

namespace App\Models;

class Package extends BaseModel
{
//    public function coursePackage()
//    {
//        return $this->hasMany(CoursePackage::class, )
//    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_package', 'package_id', 'course_id');
    }
}

