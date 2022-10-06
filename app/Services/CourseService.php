<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Services\Logic\Course\CategoryListService;
use App\Services\Logic\Course\ChapterListService;
use App\Services\Logic\Course\CourseInfoService;
use App\Services\Logic\Course\CourseListService;
use App\Services\Logic\Course\PackageListService;

class CourseService
{

    public function getCourses($params)
    {
        $service = new CourseListService();

        $pager = $service->handle($params);
        return $pager;
    }

    public function getCourse($id)
    {

        $service = new CourseInfoService();

        $course = $service->handle($id);

        if ($course['published'] == 0) {
            throw new NotFoundException();
        }
        return ['course' => $course];

    }


    public function getCategories()
    {

        $service = new CategoryListService();

        $categories = $service->handle();
        return ['categories' => $categories];

    }

    public function getCourseChapters($id)
    {

        $service = new ChapterListService();

        $chapters = $service->handle($id);
        return ['chapters' => $chapters];

    }

    public function getCoursePackages($id)
    {

        $service = new PackageListService();

        $packages = $service->handle($id);
        return ['packages' => $packages];

    }
}
