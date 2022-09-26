<?php

namespace App\Http\Controllers\Cms;

use App\Services\TagService;
use App\Validates\TagListValidate;
use App\Validates\TagSearchValidate;

class TagController extends BaseController
{
    protected $only = [];


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


}
