<?php

namespace App\Services;

use App\Enums\CourseEnums;
use App\Models\Course;

class CourseService
{

    public function countCourses()
    {
        return Course::query()->where('published', 1)->count();
    }

    public function getModelTypes()
    {
        return CourseEnums::modelTypes();
    }
}
