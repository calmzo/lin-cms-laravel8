<?php

namespace App\Http\Controllers\V1;

use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Validates\ArticleFormValidate;
use App\Validates\ArticleListValidate;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{
    protected $except = [];

    /**
     * 新建文章
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function createArticle(ArticleFormValidate $articleFormValidate)
    {
        $params = $articleFormValidate->check();
        $articleService = new ArticleService();
        $article = $articleService->createArticle($params);
        return $this->success([], '发布文章成功');
    }

    /**
     * 分类列表
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categoryService = new CategoryService();
        $list = $categoryService->categorieTreeList();
        return $this->success($list);
    }

    /**
     * 文章列表
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getArticles(ArticleListValidate $articleListValidate)
    {
        $params = $articleListValidate->check();
        $start = $params['start'] ?? null;
        $end = $params['end'] ?? null;
        $name = $params['name'] ?? null;
        $page = $params['page'] ?? 0; //分页数
        $count = $params['count'] ?? 10; //分页值
        $articleService = new ArticleService();
        $list = $articleService->getArticles($page, $count, $start, $end, $name);
        return $this->success($list);
    }

    public function getArticle($id)
    {
        $articleService = new ArticleService();
        $result = $articleService->getArticle($id);
        return $result;
    }
}
