<?php

namespace App\Services\Admin;

use App\Enums\CategoryEnums;
use App\Models\Category;

class CategoryService
{

    public function courseCategoryList()
    {
        return Category::query()->where('type', CategoryEnums::TYPE_COURSE)->get();
    }


}
