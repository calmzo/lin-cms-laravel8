<?php

namespace App\Services;

use App\Services\Logic\Vip\CourseListService;
use App\Services\Logic\Vip\UserListService;

class VipService extends BaseService
{

    public $request;

    public function getCourses()
    {
        $type = $this->getRequest()->input('type', 'discount');
        $service = new CourseListService();
        $pager = $service->handle($type);

        return $pager;
    }

    public function getUsers()
    {
        $service = new UserListService();

        $pager = $service->handle();

        return $pager;
    }

}
