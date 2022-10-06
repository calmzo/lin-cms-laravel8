<?php

namespace App\Repositories;

use App\Models\CourseCategory;

class CourseCategoryRepository extends BaseRepository
{
    public function findByCategoryIds($categoryIds)
    {
        return CourseCategory::query()
            ->whereIn('category_id', $categoryIds)
            ->get();
    }

}
