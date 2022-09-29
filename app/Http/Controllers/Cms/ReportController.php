<?php

namespace App\Http\Controllers\Cms;

use App\Services\Admin\ReportService;
use App\Validates\Report\ReportArticleListValidate;

class ReportController extends BaseController
{
    protected $except = [];

    /**
     * @groupRequired
     * @permission('举报文章列表','举报管理')
     * @param ReportArticleListValidate $articleListValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function getArticles(ReportArticleListValidate $articleListValidate)
    {
        $params = $articleListValidate->check();
        $reportService = new ReportService();
        $list = $reportService->getArticles($params);
        return $this->successPaginate($list);
    }

}
