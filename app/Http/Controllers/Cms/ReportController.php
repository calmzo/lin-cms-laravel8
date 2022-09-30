<?php

namespace App\Http\Controllers\Cms;

use App\Services\Admin\ReportService;
use App\Validates\Report\ReportAnswerListValidate;
use App\Validates\Report\ReportArticleListValidate;
use App\Validates\Report\ReportCommentListValidate;
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

    /**
     * @groupRequired
     * @permission('举报问题列表','举报管理')
     * @param ReportQuestionListValidate $reportQuestionListValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function getQuestions(ReportQuestionListValidate $reportQuestionListValidate)
    {
        $params = $reportQuestionListValidate->check();
        $reportService = new ReportService();
        $list = $reportService->getQuestions($params);
        return $this->successPaginate($list);
    }

    /**
     * @groupRequired
     * @permission('举报答案列表','举报管理')
     * @param ReportQuestionListValidate $reportQuestionListValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function getAnswers(ReportAnswerListValidate $reportAnswerListValidate)
    {
        $params = $reportAnswerListValidate->check();
        $reportService = new ReportService();
        $list = $reportService->getAnswers($params);
        return $this->successPaginate($list);
    }


    /**
     * @groupRequired
     * @permission('举报评论列表','举报管理')
     * @param ReportCommentListValidate $reportCommentListValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function getComments(ReportCommentListValidate $reportCommentListValidate)
    {
        $params = $reportCommentListValidate->check();
        $reportService = new ReportService();
        $list = $reportService->getComments($params);
        return $this->successPaginate($list);
    }

}
