<?php

namespace App\Http\Controllers\Cms;

use App\Events\Logger;
use App\Services\Admin\TagService;
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
        $tagService = new TagService();
        return $this->successPaginate($tagService->getTags($params));
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
        $tagService = new TagService();
        return $this->successPaginate($tagService->searchTags($params));
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
