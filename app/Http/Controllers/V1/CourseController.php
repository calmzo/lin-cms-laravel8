<?php

namespace App\Http\Controllers\V1;

use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends BaseController
{
    //
    protected $only = ['getCourse'];

    public function getCourses(Request $request)
    {
        $params = $request->all();
        $service = new CourseService();
        $result = $service->getCourses($params);
        return $this->successPaginate($result);
    }

    public function getCourse($id)
    {
        $service = new CourseService();
        $result = $service->getCourse($id);
        return $this->success($result);
    }

    public function getCategories()
    {
        $service = new CourseService();
        $result = $service->getCategories();
        return $this->success($result);
    }


    public function getCourseChapters($id)
    {
        $service = new CourseService();
        $result = $service->getCourseChapters($id);
        return $this->success($result);
    }


    public function getCoursePackages($id)
    {
        $service = new CourseService();
        $result = $service->getCoursePackages($id);
        return $this->success($result);
    }

    public function getCourseConsults($id, Request $request)
    {
        $params = $request->all();
        $service = new CourseService();
        $result = $service->getCourseConsults($id, $params);
        return $this->successPaginate($result);
    }
}
