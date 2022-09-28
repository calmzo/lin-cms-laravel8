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

    public function updateArticle(ArticleFormValidate $articleFormValidate, $id)
    {
        $params = $articleFormValidate->check();
        $articleService = new ArticleService();
        $article = $articleService->updateArticle($id, $params);
        return $this->success([], '更新文章成功');
    }

    public function deleteArticle($id)
    {
        $articleService = new ArticleService();
        $article = $articleService->deleteArticle($id);
        return $this->success([], '删除文章成功');
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

    /**
     * 文章详情
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Forbidden
     * @throws \App\Exceptions\NotFoundException
     */
    public function getArticle($id)
    {
        $articleService = new ArticleService();
        $result = $articleService->getArticle($id);
        return $this->success($result);
    }


    /**
     * 添加枚举列表
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getEnums()
    {
        $articleService = new ArticleService();
        $result = $articleService->getEnums();
        return $this->success($result);
    }

    /**
     * 评论开启关闭
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function closeArticle($id)
    {
        $articleService = new ArticleService();
        $result = $articleService->closeArticle($id);
        return $this->success($result);
    }


    /**
     * 仅我可见开启关闭
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function privateArticle($id)
    {
        $articleService = new ArticleService();
        $result = $articleService->privateArticle($id);
        return $this->success($result);
    }


    /**
     * 收藏
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function favoriteArticle($id)
    {
        $articleService = new ArticleService();
        $result = $articleService->favoriteArticle($id);
        return $this->success($result);
    }

    public function likeArticle($id)
    {
        $articleService = new ArticleService();
        $result = $articleService->likeArticle($id);
        return $this->success($result);
    }
}
