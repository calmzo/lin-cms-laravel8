<?php

namespace App\Http\Controllers\Cms;

use App\Events\Logger;
use App\Listeners\LoggerNotification;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    protected $only = [];


    /**
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
     * @validate('ResetPasswordValidator')
     * @param Request $request
     * @param $id
     * @return Json
     * @throws NotFoundException
     */
    public function changeUserPassword(Request $request, $id)
    {
        $newPassword = $request->put('new_password');
        AdminService::changeUserPassword($id, $newPassword);
        Hook::listen('logger', "修改了用户ID为{$id}的密码");

        return writeJson(200, null, '修改成功', 4);
    }

    /**
     * @adminRequired
     * @permission('删除用户','管理员','hidden')
     * @param int $id
     * @param('id','用户id','require|integer')
     * @return Json
     * @throws NotFoundException
     * @throws OperationException
     */
    public function deleteUser(int $id)
    {
        AdminService::deleteUser($id);
        Hook::listen('logger', "删除了用户ID为：{$id}的用户");
        return writeJson(201, $id, '删除用户成功', 5);
    }

    /**
     * @adminRequired
     * @permission('管理员更新用户信息','管理员','hidden')
     * @param Request $request
     * @param('id','用户id','require|integer')
     * @param('group_ids','分组id','require|array|min:1')
     * @return Json
     * @throws NotFoundException
     * @throws OperationException
     * @throws ForbiddenException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
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
     * @adminRequired
     * @permission('查询所有分组','管理员','hidden')
     * @return array|PDOStatement|string|\think\Collection|Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws NotFoundException
     */
    public function getGroupAll()
    {
        return AdminService::getAllGroups();
    }

    /**
     * @adminRequired
     * @permission('查询一个权限组及其权限','管理员','hidden')
     * @param int $id
     * @param('id','分组id','require|integer')
     * @return Query
     * @throws DbException
     * @throws NotFoundException
     */
    public function getGroup(int $id)
    {
        return AdminService::getGroup($id);
    }

    /**
     * @adminRequired
     * @permission('新建一个权限组','管理员','hidden')
     * @param Request $request
     * @param('name','分组名字','require')
     * @param('permission_ids','权限id','require|array|min:1')
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws NotFoundException
     * @throws OperationException
     */
    public function createGroup(Request $request)
    {
        $name = $request->input('name');
        $info = $request->input('info');
        $permissionIds = $request->post('permission_ids');

        $groupId = AdminService::createGroup($name, $info, $permissionIds);
        Logger::dispatch("创建了分组：{$name}");
        return $this->success($groupId,"新增分组成功");
//        writeJson(201, $groupId, '新增分组成功', 15);
    }

    /**
     * @adminRequired
     * @permission('更新一个权限组','管理员','hidden')
     * @param Request $request
     * @param int $id
     * @param('id','分组id','require|integer')
     * @param('info','分组信息','require')
     * @param('name','分组名字','require')
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws NotFoundException
     */
    public function updateGroup(Request $request, int $id)
    {
        $name = $request->input('name');
        $info = $request->input('info');

        $res = AdminService::updateGroup($id, $name, $info);
        Logger::dispatch("更新了id为{$id}的分组");
        return $this->success($res,"更新分组信息成功");
//        return writeJson(200, $res, '更新分组信息成功', 7);
    }

    /**
     * @adminRequired
     * @permission('更新一个权限组','管理员','hidden')
     * @param int $id
     * @param('id','分组id','require|integer')
     * @return Json
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws OperationException
     */
    public function deleteGroup(int $id)
    {
        AdminService::deleteGroup($id);
        Logger::dispatch("删除了id为{$id}的分组");
        return $this->success([],"删除分组成功");
    }

    /**
     * @adminRequired
     * @permission('分配多个权限','管理员','hidden')
     * @param Request $request
     * @param('group_id','分组id','require|integer')
     * @param('permission_ids','权限id','require|array|min:1')
     * @return Json
     * @throws DbException
     * @throws NotFoundException
     * @throws OperationException
     */
    public function dispatchPermissions(Request $request)
    {
        $groupId = $request->input('group_id');
        $permissionIds = $request->input('permission_ids');

        AdminService::dispatchPermissions($groupId, $permissionIds);
        Logger::dispatch("修改了分组ID为{$groupId}的权限");
        return $this->success([],'分配权限成功');
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
