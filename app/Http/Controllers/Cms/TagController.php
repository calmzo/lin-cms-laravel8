<?php

namespace App\Http\Controllers\Cms;

use App\Events\Logger;
use App\Services\TagService;
use App\Validates\TagFormValidate;
use App\Validates\TagListValidate;
use App\Validates\TagSearchValidate;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    protected $except = [];


    /**
     * @groupRequired
     * @permission('查询所有标签','标签')
     * @param TagListValidate $tagListValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function getTags(TagListValidate $tagListValidate)
    {
        $params = $tagListValidate->check();
        $start = $params['start'] ?? null;
        $end = $params['end'] ?? null;
        $name = $params['name'] ?? null;
        $page = $params['page'] ?? 0; //分页数
        $count = $params['count'] ?? 10; //分页值
        $tagService = new TagService();
        return $this->successPaginate($tagService->getTags($page, $count, $start, $end, $name));
    }


    /**
     * @groupRequired
     * @permission('搜索标签','标签')
     * @param TagSearchValidate $tagSearchValidate
     * @return array|mixed
     * @throws \App\Exceptions\ValidateException
     */
    public function searchTags(TagSearchValidate $tagSearchValidate)
    {
        $params = $tagSearchValidate->check();
        $start = $params['start'] ?? null;
        $end = $params['end'] ?? null;
        $name = $params['name'] ?? null;
        $keyword = $params['keyword'] ?? null;
        $page = $params['page'] ?? 0; //分页数
        $count = $params['count'] ?? 10; //分页值
        $tagService = new TagService();
        return $this->successPaginate($tagService->searchTags($page, $count, $start, $end, $name, $keyword));
    }

    /**
     * @groupRequired
     * @permission('添加标签','标签')
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function createTag(TagFormValidate $tagFormValidate)
    {
        $params = $tagFormValidate->check();
        $tagService = new TagService();
        $tag = $tagService->createTag($params);
        Logger::dispatch("新增了标签：{$params['name']}");
        return $this->success($tag, "新增标签成功");

    }


}
