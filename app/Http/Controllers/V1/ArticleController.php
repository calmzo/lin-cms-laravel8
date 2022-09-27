<?php

namespace App\Http\Controllers\V1;

use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Validates\ArticleFormValidate;
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
}
