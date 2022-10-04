<?php

namespace App\Services;

use App\Enums\CourseEnums;

class CourseService
{

    public function getModelTypes()
    {
        return CourseEnums::modelTypes();
    }
}
