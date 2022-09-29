<?php

namespace App\Http\Controllers\Cms;

use App\Services\Admin\ReportService;
use App\Validates\Report\ReportArticleListValidate;
use App\Validates\Report\ReportQuestionListValidate;

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

    public function getQuestions(ReportQuestionListValidate $reportQuestionListValidate)
    {
        $params = $reportQuestionListValidate->check();
        $reportService = new ReportService();
        $list = $reportService->getQuestions($params);
        return $this->successPaginate($list);
    }

}
