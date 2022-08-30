<?php

namespace App\Http\Controllers\V1;

use App\Events\Logger;
use App\Http\Controllers\Cms\BaseController;
use App\Services\OrganizationService;
use Illuminate\Http\Request;

class OrganizationController extends BaseController
{
    public $only = [];

    public function getOrganization($id)
    {
        $res = OrganizationService::getOrganization($id);
        return $this->success($res);
    }

    public function getOrganizations()
    {
        $res = OrganizationService::getOrganizations();
        return $this->success($res);
    }

    public function createOrganization(Request $request)
    {
        $params = $request->all();
        $res = OrganizationService::createOrganization($params);
        return $this->success($res, '新建组织架构成功');
    }


    /**
     * 编辑图书
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\OperationException
     */
    public function updateBook(Request $request, $id)
    {
        $params = $request->all();
        OrganizationService::updateOrganization($id, $params);
        return $this->success([], '更新部门成功');
    }

    /**
     * @groupRequired
     * @permission('删除部门','部门')
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function deleteBook($id)
    {
        OrganizationService::deleteOrganization($id);
        Logger::dispatch("删除了id为{$id}的部门");
        return $this->success([], '删除部门成功');
    }

}
