<?php

namespace App\Validators;

use App\Caches\CategoryCache;
use App\Enums\CourseEnums;
use App\Exceptions\BadRequestException;
use App\Models\Course;
use App\Utils\CodeResponse;

class CourseQueryValidator extends BaseValidator
{

    public function checkTopCategory($id)
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_query.invalid_top_category');
        }

        return $category->id;
    }

    public function checkSubCategory($id)
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_query.invalid_sub_category');
        }

        return $category->id;
    }

    public function checkLevel($level)
    {
        $types = CourseEnums::levelTypes();

        if (!isset($types[$level])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_query.invalid_level');
        }

        return $level;
    }

    public function checkModel($model)
    {
        $types = CourseEnums::modelTypes();

        if (!isset($types[$model])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_query.invalid_model');
        }

        return $model;
    }

    public function checkSort($sort)
    {
        $types = CourseEnums::sortTypes();

        if (!isset($types[$sort])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_query.invalid_sort');
        }

        return $sort;
    }

}
