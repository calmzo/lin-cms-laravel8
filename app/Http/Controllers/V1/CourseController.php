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
}
