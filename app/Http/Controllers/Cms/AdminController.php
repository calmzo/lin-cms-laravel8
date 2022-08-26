<?php

namespace App\Http\Controllers\Cms;

use App\Events\Logger;
use App\Services\Admin\AdminService;
use App\Validates\User\ResetPasswordValidate;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    protected $only = [];


    /**
     * 查询所有可分配的权限
     * @adminRequired
     * @permission('查询所有可分配的权限','管理员','hidden')
     * @return array
     * @throws \ReflectionException
     */
    public function getAllPermissions()
    {
        return AdminService::getAllPermissions();
    }

    /**
     * @adminRequired
     * @permission('查询所有用户','管理员','hidden')
     * @param Request $request
     * @return array|mixed
     */
    public function getAdminUsers(Request $request)
    {
        $page = $request->input('page', 0);
        $count = $request->input('count', 10);
        $groupId = $request->input('group_id');
        return $this->successPaginate(AdminService::getUsers($page, $count, $groupId));
    }

    /**
     * @adminRequired
     * @permission('修改用户密码','管理员','hidden')
     * @param ResetPasswordValidate $resetPasswordValidate
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\ValidateException
     */
    public function changeUserPassword(ResetPasswordValidate $resetPasswordValidate, $id)
    {
        $param = $resetPasswordValidate->check();
        AdminService::changeUserPassword($id, $param['new_password']);
        Logger::dispatch("修改了用户ID为{$id}的密码");
        return $this->success([], '修改成功');
    }

    /**
     * @adminRequired
     * @permission('删除用户','管理员','hidden')
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\OperationException
     */
    public function deleteUser(int $id)
    {
        AdminService::deleteUser($id);
        Logger::dispatch("删除了用户ID为：{$id}的用户");
        return $this->success($id, '删除用户成功');
    }

    /**
     * 管理员更新用户信息
     * @adminRequired
     * @permission('管理员更新用户信息','管理员','hidden')
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\OperationException
     */
    public function updateUser(Request $request, $id)
    {
        $groupIds = $request->input('group_ids');
        AdminService::updateUserInfo($id, $groupIds);
        Logger::dispatch("更新了用户：{$id}的所属分组");
        return $this->success($id, '更新用户成功');

//        return writeJson(201, $id, '更新用户成功', 6);
    }

    /**
     * 查询所有分组
     * @adminRequired
     * @permission('查询所有分组','管理员','hidden')
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \App\Exceptions\NotFoundException
     */
    public function getGroupAll()
    {
        return AdminService::getAllGroups();
    }

    /**
     * @adminRequired
     * @permission('查询一个权限组及其权限','管理员','hidden')
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     * @throws \App\Exceptions\NotFoundException
     */
    public function getGroup(int $id)
    {
        return AdminService::getGroup($id);
    }

    /**
     * @adminRequired
     * @permission('新建一个权限组','管理员','hidden')
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\OperationException
     */
    public function createGroup(Request $request)
    {
        $name = $request->input('name');
        $info = $request->input('info');
        $permissionIds = $request->post('permission_ids');

        $groupId = AdminService::createGroup($name, $info, $permissionIds);
        Logger::dispatch("创建了分组：{$name}");
        return $this->success($groupId, "新增分组成功");
//        writeJson(201, $groupId, '新增分组成功', 15);
    }

    /**
     * @adminRequired
     * @permission('更新一个权限组','管理员','hidden')
     * @param Request $request
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\NotFoundException
     */
    public function updateGroup(Request $request, int $id)
    {
        $name = $request->input('name');
        $info = $request->input('info');

        $res = AdminService::updateGroup($id, $name, $info);
        Logger::dispatch("更新了id为{$id}的分组");
        return $this->success($res, "更新分组信息成功");
//        return writeJson(200, $res, '更新分组信息成功', 7);
    }

    /**
     * @adminRequired
     * @permission('删除一个权限组','管理员','hidden')
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\OperationException
     */
    public function deleteGroup(int $id)
    {
        AdminService::deleteGroup($id);
        Logger::dispatch("删除了id为{$id}的分组");
        return $this->success([], "删除分组成功");
    }

    /**
     * @adminRequired
     * @permission('分配多个权限','管理员','hidden')
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\OperationException
     */
    public function dispatchPermissions(Request $request)
    {
        $groupId = $request->input('group_id');
        $permissionIds = $request->input('permission_ids');

        AdminService::dispatchPermissions($groupId, $permissionIds);
        Logger::dispatch("修改了分组ID为{$groupId}的权限");
        return $this->success([], '分配权限成功');
    }

    /**
     * @adminRequired
     * @permission('删除多个权限','管理员','hidden')
     * @param Request $request
     * @param('group_id','分组id','require|integer')
     * @param('permission_ids','权限id','require|array|min:1')
     * @return Json
     * @throws DbException
     * @throws NotFoundException
     */
    public function removePermissions(Request $request)
    {
        $groupId = $request->post('group_id');
        $permissionIds = $request->post('permission_ids');

        $deleted = AdminService::removePermissions($groupId, $permissionIds);

        Hook::listen('logger', "修改了分组ID为{$groupId}的权限");
        return writeJson(200, $deleted, '删除权限成功', 10);
    }
}
