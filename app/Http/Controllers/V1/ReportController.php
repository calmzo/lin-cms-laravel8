<?php

namespace App\Http\Controllers\V1;

use App\Services\Logic\Report\ReportCreateService;
use App\Validates\ReportFormValidate;

class ReportController extends BaseController
{
    public $except = [];


    public function createReport(ReportFormValidate $reportFormValidate)
    {
        $params = $reportFormValidate->check();
        $service = new ReportCreateService();

        $service->handle($params);
        return $this->success([], '举报成功');
    }

}
