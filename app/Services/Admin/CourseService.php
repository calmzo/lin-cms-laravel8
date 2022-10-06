<?php

namespace App\Services\Admin;

use App\Enums\CourseEnums;

class CourseService extends BaseService
{
    public function getModelTypes()
    {
        return CourseEnums::modelTypes();
    }

}
