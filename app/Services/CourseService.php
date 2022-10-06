<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Services\Logic\Course\CategoryListService;
use App\Services\Logic\Course\ChapterListService;
use App\Services\Logic\Course\ConsultListService;
use App\Services\Logic\Course\CourseFavoriteService;
use App\Services\Logic\Course\CourseInfoService;
use App\Services\Logic\Course\CourseListService;
use App\Services\Logic\Course\PackageListService;
use App\Services\Logic\Course\ReviewListService;

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

    public function getCourseConsults($id, $params)
    {

        $service = new ConsultListService();

        $pager = $service->handle($id, $params);
        return $pager;

    }

    public function getCourseReviews($id, $params)
    {

        $service = new ReviewListService();

        $pager = $service->handle($id, $params);
        return $pager;

    }

    public function favoriteCourse($id)
    {

        $service = new CourseFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';
        return ['data' => $data, 'msg' => $msg];

    }
}
