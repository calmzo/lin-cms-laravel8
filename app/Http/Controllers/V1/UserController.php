<?php

namespace App\Http\Controllers\V1;


use App\Services\UserService;
use App\Validates\V1\User\ArticleListValidate;
use App\Validates\V1\User\QuestionListValidate;

class UserController extends BaseController
{
    public $except = [];

    /**
     * 查询用户信息
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getUser($id)
    {
        $service = new UserService();
        $user = $service->getUser($id);
        return $this->success($user);
    }

    /**
     * 查询用户文章列表
     * @param ArticleListValidate $articleListValidate
     * @param $id
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function getArticles(ArticleListValidate $articleListValidate, $id)
    {
        $params = $articleListValidate->check();
        $service = new UserService();
        $pager = $service->getArticles($id, $params);
        return $this->successPaginate($pager);
    }

    public function getQuestions(QuestionListValidate $questionListValidate, $id)
    {
        $params = $questionListValidate->check();
        $service = new UserService();
        $pager = $service->getQuestions($id, $params);
        return $this->successPaginate($pager);
    }

}
