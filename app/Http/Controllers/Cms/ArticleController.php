<?php

namespace App\Http\Controllers\Cms;


use App\Events\Logger;
use App\Services\ArticleService;
use App\Validates\ArticleFormValidate;
use App\Validates\ArticleListValidate;
use App\Validates\ArticleSearchValidate;

class ArticleController extends BaseController
{
    protected $except = [];

    /**
     * @groupRequired
     * @permission('查询所有文章','文章管理')
     * @param ArticleListValidate $articleListValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function getArticles(ArticleListValidate $articleListValidate)
    {
        $params = $articleListValidate->check();
        $articleService = new ArticleService();
        return $this->successPaginate($articleService->getArticles($params));
    }


    /**
     * @groupRequired
     * @permission('搜索文章','文章管理')
     * @param ArticleSearchValidate $articleSearchValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function searchArticle(ArticleSearchValidate $articleSearchValidate)
    {
        $params = $articleSearchValidate->check();
        $start = $params['start'] ?? null;
        $end = $params['end'] ?? null;
        $name = $params['name'] ?? null;
        $keyword = $params['keyword'] ?? null;
        $page = $params['page'] ?? 0; //分页数
        $count = $params['count'] ?? 10; //分页值
        $articleService = new ArticleService();
        return $this->successPaginate($articleService->searchArticles($page, $count, $start, $end, $name, $keyword));
    }

    /**
     * @groupRequired
     * @permission('添加文章','文章管理')
     * @param ArticleFormValidate $articleFormValidate
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ValidateException
     */
    public function createTag(ArticleFormValidate $articleFormValidate)
    {
        $params = $articleFormValidate->check();
        $articleService = new ArticleService();
        $tag = $articleService->createArticle($params);
        Logger::dispatch("新增了文章：{$params['name']}");
        return $this->success($tag, "新增文章成功");

    }


}
