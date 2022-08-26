<?php

namespace App\Services\Admin;

use App\Enums\GroupLevelEnums;
use App\Enums\MountTypeEnums;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\OperationException;
use App\Models\Admin\LinGroup;
use App\Models\Admin\LinGroupPermission;
use App\Models\Admin\LinPermission;
use App\Models\Admin\LinUser;
use App\Models\Admin\LinUserGroup;
use app\super\controller\Code;
use App\Utils\CodeResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Lib\Authenticator\PermissionScan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminService
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getAllPermissions(): array
    {
        $permissionList = (new PermissionScan())->run();
        foreach ($permissionList as $permission) {
            $model = LinPermission::query()->where('name', $permission['name'])
                ->where('module', $permission['module'])
                ->first();
            if (!$model) {
                self::createPermission($permission['name'], $permission['module']);
            }
        }

        $permissions = LinPermission::query()->where('mount', MountTypeEnums::MOUNT)
            ->get()->toArray();
        $result = [];
        foreach ($permissions as $permission) {
            $result[$permission['module']][] = $permission;
        }
        return $result;
    }

    /**
     * @param int $page
     * @param int $count
     * @param int|null $groupId
     * @return LengthAwarePaginator
     */
    public static function getUsers(int $page, int $count, int $groupId = null): LengthAwarePaginator
    {
        list($page, $count) = paginate($page, $count);
        $query = LinUser::query();
        if ($groupId) {
            $query->whereHas('groups', function ($query) use ($groupId){
                $query->where('group_id', $groupId);
            });
        }
        $users = $query
            ->where('username', '<>', 'root')
            ->with('groups')
            ->paginate($count, ['*'], 'page', $page);
        return $users;
    }

    /**
     * @param int $uid
     * @param string $newPassword
     * @throws NotFoundException
     */
    public static function changeUserPassword(int $uid, string $newPassword): void
    {
        $user = LinUser::query()->find($uid);
        if (!$user) {
            throw new NotFoundException();
        }
        $user->password = Hash::make($newPassword);
        $user->save();

    }

    /**
     * @param int $uid
     * @throws NotFoundException
     * @throws OperationException
     */
    public static function deleteUser(int $uid): void
    {
        $user = LinUser::query()->find($uid);
        if (!$user) {
            throw new NotFoundException();
        }

        DB::beginTransaction();
        try {
            $user->groups()->detach();
            $user->delete();
            DB::commit();
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollback();
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, '删除用户失败');
        }
    }

    /**
     * @param int $uid
     * @param array $groupIds
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws OperationException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function updateUserInfo(int $uid, array $groupIds): void
    {
        $user = LinUser::query()->where('id', $uid)->first();
        if (!$user) {
            throw new NotFoundException();
        }

        $userGroupIds = LinUserGroup::query()->where('user_id', $uid)->pluck('group_id');
        $isAdmin = LinGroup::query()->where('level', GroupLevelEnums::ROOT)
            ->whereIn('id', $userGroupIds)
            ->first();
        if ($isAdmin) {
            throw new ForbiddenException(['code' => 10078, 'msg' => '不允许调整root分组信息']);
        }

        foreach ($userGroupIds as $groupId) {
            $group = LinGroup::query()->where('id', $groupId)->first();
            if ($group['level'] === GroupLevelEnums::ROOT) {
                throw new ForbiddenException([10073, '不允许添加用户到root分组']);
            }

            if (!$group) {
                throw new NotFoundException(['10077', '资源不存在']);
            }
        }

        DB::beginTransaction();
        try {
            $user->groups()->detach();
            $user->groups()->attach($groupIds);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, '更新用户分组失败');
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws NotFoundException
     */
    public static function getAllGroups()
    {
        $groups = LinGroup::query()->where('level', '<>', GroupLevelEnums::ROOT)->get();
        if ($groups->isEmpty()) {
            throw new NotFoundException();
        }
        return $groups;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     * @throws NotFoundException
     */
    public static function getGroup(int $id)
    {
        $group = LinGroup::query()->where('level', '<>', GroupLevelEnums::ROOT)
            ->where('id', $id)->with('permissions')->first();
        if (!$group) {
            throw new NotFoundException();
        }

        return $group;
    }

    /**
     * @param string $name
     * @param string $info
     * @param array $permissionIds
     * @return int
     * @throws NotFoundException
     * @throws OperationException
     */
    public static function createGroup(string $name, string $info, array $permissionIds): int
    {
        $isExist = LinGroup::query()->where('name', $name)->first();
        if ($isExist) {
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, '分组名已存在');
        }

        foreach ($permissionIds as $permissionId) {
            $permission = LinPermission::query()->where('mount', MountTypeEnums::MOUNT)
                ->where('id', $permissionId)->first();
            if (!$permission) {
                throw new NotFoundException([10231, '分配了不存在的权限']);
            }
        }

        DB::beginTransaction();
        try {
            $group = LinGroup::query()->create(['name' => $name, 'info' => $info]);
            foreach ($permissionIds as $permissionId) {
                $permissionData[] = [
                    'group_id' => $group->id ?? 0,
                    'permission_id' => $permissionId,
                ];
            }
            LinGroupPermission::query()->insert($permissionData);
            DB::commit();
            return $group->id;
        } catch (\Exception $ex) {
            DB::rollback();
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, "新增分组失败:{$ex->getMessage()}");
        }

    }

    /**
     * @param int $id
     * @param string $name
     * @param string $info
     * @return int
     * @throws NotFoundException
     */
    public static function updateGroup(int $id, string $name, string $info): int
    {
        $group = LinGroup::query()->where('level', '<>', GroupLevelEnums::ROOT)->where('id', $id)
            ->first();

        if (!$group) {
            throw new NotFoundException();
        }
        $group->name = $name;
        $group->info = $info;
        $group->save();
        return $group->id;
    }

    /**
     * @param int $id
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws OperationException
     */
    public static function deleteGroup(int $id): void
    {
        $group = LinGroup::query()->where('id', $id)->first();

        if (!$group) {
            throw new NotFoundException();
        }

        if ($group->level === GroupLevelEnums::ROOT) {
            throw new ForbiddenException(CodeResponse::FORBIDDEN_EXCEPTION, '不允许删除root分组');
        }

        if ($group->level === GroupLevelEnums::GUEST) {
            throw new ForbiddenException(CodeResponse::FORBIDDEN_EXCEPTION, '不允许删除guest分组');
        }

        DB::beginTransaction();
        try {
            $group->delete();
            $group->permissions()->detach();
            $group->users()->detach();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, "删除分组失败:{$ex->getMessage()}");
        }
    }

    /**
     * @param int $id
     * @param array $permissionIds
     * @throws NotFoundException
     * @throws OperationException
     */
    public static function dispatchPermissions(int $id, array $permissionIds)
    {
        $group = LinGroup::query()
            ->where('level', '<>', GroupLevelEnums::ROOT)
            ->where('id', $id)
            ->first();
        if (!$group) {
            throw new NotFoundException();
        }

        foreach ($permissionIds as $permissionId) {
            $permission = LinPermission::query()->where('mount', MountTypeEnums::MOUNT)->where('id', $permissionId)->first();
            if (!$permission) {
                throw new NotFoundException(CodeResponse::PERMISSION_NOT_EXIST);
            }
        }

        try {
            $group->permissions()->attach($permissionIds);
        } catch (\Exception $ex) {
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, '权限分配失败');
        }
    }

    /**
     * @param int $id
     * @param array $permissionIds
     * @return int
     * @throws NotFoundException
     */
    public static function removePermissions(int $id, array $permissionIds): int
    {
        $group = LinGroup::query()->where('level', '<>', GroupLevelEnums::ROOT)->where('id', $id)->first();
        if (!$group) {
            throw new NotFoundException();
        }

        foreach ($permissionIds as $permissionId) {
            $permission = LinPermission::query()->where('mount', MountTypeEnums::MOUNT)->where('id', $permissionId)->first();
            if (!$permission) {
                throw new NotFoundException([10231, '分配了不存在的权限']);
            }
        }
        return $group->permissions()->detach($permissionIds);
    }

    public static function createPermission(string $name, string $module): LinPermission
    {
        return LinPermission::create(['name' => $name, 'module' => $module, 'mount' => 1]);
    }
}
