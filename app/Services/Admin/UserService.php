<?php

namespace App\Services\Admin;


use App\Enums\GroupLevelEnums;
use App\Enums\MountTypeEnums;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\AuthFailedException;
use App\Exceptions\OperationException;
use App\Exceptions\RepeatException;
use App\Exceptions\UserException;
use App\Models\Admin\LinAuth;
use App\Models\Admin\LinGroup;
use App\Models\Admin\LinGroupPermission;
use App\Models\Admin\LinPermission;
use App\Models\Admin\LinUser;
use App\Models\Admin\LinUserGroup;
use App\Services\Token\LoginTokenService;
use App\Utils\CodeResponse;
use App\Models\Admin\LinUser as LinUserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    /**
     * @param array $params
     * @return LinUserModel
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws OperationException
     * @throws RepeatException
     */
    public static function createUser(array $params): LinUserModel
    {
        $user = LinUserModel::query()->where('username', $params['username'])->first();
        if ($user) {
            throw new RepeatException(CodeResponse::VALIDATE_EXCEPTION, '用户名已存在');
        }
        if (isset($params['email'])) {
            $user = LinUserModel::query()->where('email', $params['email'])->first();
            if ($user) {
                throw new RepeatException(CodeResponse::VALIDATE_EXCEPTION, '邮箱地址已存在');
            }
        }
        if (isset($params['group_ids'])) {
            $groups = LinGroup::query()->whereIn('id', $params['group_ids'])->get();
            foreach ($groups as $group) {
                if ($group['level'] === GroupLevelEnums::ROOT) {
                    throw new ForbiddenException(CodeResponse::FORBIDDEN, '不允许分配用户到root分组');
                }
            }
            if ($groups->isEmpty()) {
                throw new NotFoundException();
            }
        }
        $user = self::registerUser($params);
        return $user;
    }

    /**
     * @param string $username
     * @param string $password
     * @return Model
     * @throws AuthFailedException
     * @throws NotFoundException
     */
    public static function verify(string $username, string $password): Model
    {
        $user = self::getByUsername($username);
        if (empty($user)) {
            throw new NotFoundException(CodeResponse::EXCEPTION, '用户不存在');
        }
        //验证密码
        $is_pass = Hash::check($password, $user->password);
        if (!$is_pass) {
            throw new AuthFailedException();
        }
        //更新登录情况
//        $user->last_login_time = now();
//        $user->last_login_ip = $request->getClientIp();
//        if (!$user->save()) {
//            return $this->fail(CodeResponse::UPDATE_DATA_FAILED);
//        }
        return $user;
    }

    public static function generateTokenExtend(Model $linUserIdentityModel)
    {
        $user = LinUserModel::get($linUserIdentityModel['user_id']);
        $userPermissions = self::getPermissions($user->getAttr('id'));
        return [
            'id' => $user->getAttr('id'),
            'identifier' => $linUserIdentityModel->getAttr('identifier'),
            'email' => $user->getAttr('email'),
            'admin' => $userPermissions['admin'],
            'permissions' => $userPermissions['permissions'],
        ];
    }

    public static function getPermissions(int $uid): array
    {
        $user = LinUser::query()->where('id', $uid)->first();
        $user = $user->toArray() ?? [];
        $groupIds = LinUserGroup::query()->where('user_id', $uid)
            ->pluck('group_id');
        $root = LinGroup::query()->where('level', GroupLevelEnums::ROOT)
            ->whereIn('id', $groupIds)->first();
        $user['admin'] = $root ? true : false;

        if ($root) {
            $permissions = LinPermission::query()->where('mount', MountTypeEnums::MOUNT)->get()->toArray();
            $user['permissions'] = formatPermissions($permissions);
        } else {
            $permissionIds = LinGroupPermission::query()->whereIn('group_id', $groupIds)
                ->pluck('permission_id');
            $permissions = LinPermission::query()->where('mount', MountTypeEnums::MOUNT)
                ->whereIn('id', $permissionIds)->get()->toArray();

            $user['permissions'] = formatPermissions($permissions);

        }

        return $user;
    }

    /**
     * @param int $uid
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function getInformation(int $uid)
    {
        return LinUser::query()->where('id', $uid)->with('groups')->first();
    }

    /**
     * @param array $params
     * @return int
     * @throws RepeatException
     */
    public static function updateUser(array $params): int
    {
        $user = LoginTokenService::user();
        if (isset($params['username']) && $params['username'] !== $user['username']) {
            $isExit = LinUser::query()->where('username', $params['username'])
                ->first();
            if ($isExit) {
                throw new RepeatException(CodeResponse::REPEAT_EXCEPTION, '用户名已被占用');
            }
        }
        if (isset($params['email']) && $params['email'] !== $user['email']) {
            $isExit = LinUser::query()->where('email', $params['email'])
                ->first();
            if ($isExit) {
                throw new RepeatException(CodeResponse::REPEAT_EXCEPTION, '邮箱已被占用');
            }
        }
        return $user->update($params);
    }

    /**
     * @param string $oldPassword
     * @param string $newPassword
     * @return int
     * @throws AuthFailedException
     */
    public static function changePassword(string $oldPassword, string $newPassword): int
    {
        $user = LoginTokenService::user();
        $is_pass = Hash::check($oldPassword, $user->password);
        if (!$is_pass) {
            throw new AuthFailedException('旧密码错误');
        }
        $user->password = Hash::make($newPassword);
        return $user->save();
    }

    /**
     * @param array $params
     * @return LinUserModel
     * @throws OperationException
     */
    private static function registerUser(array $params): LinUserModel
    {
        DB::beginTransaction();
        try {
            $user = new LinUser();
            $user->username = $params['username'];
            $user->nickname = $params['username'];
            $user->password = Hash::make($params['password']);
            $user->email = $params['email'];
            $user->avatar = 'https://yanxuan.nosdn.127.net/80841d741d7fa3073e0ae27bf487339f.jpg?imageView&quality=90&thumbnail=64x64';
//        $user->last_login_time = now();
//        $user->last_login_ip = $request->getClientIp();
            $user->save();

            // 判断是否同时分配了分组
            if (isset($params['group_ids']) && count($params['group_ids']) > 0) {
                $user->groups()->attach($params['group_ids']);
            } else {
                //  没有分配分组，添加到游客分组
                $group = LinGroup::query()->where('level', GroupLevelEnums::GUEST)->first();
                $user->groups()->attach([$group['id']]);
            }
            DB::commit();
            return $user;
        } catch (\Exception $ex) {
            DB::rollback();
            throw new OperationException(['msg' => "注册用户失败：{$ex->getMessage()}"]);
        }

    }

    /**
     * 根据用户名获取用户
     * @param string $username
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByUsername(string $username)
    {
        return LinUserModel::query()->where('username', $username)->first();
    }


    public static function getUserByUID($uid)
    {
        try {
            $user = LinUser::query()->where('id', $uid)->first()->toArray();
        } catch (\Exception $ex) {
            throw new UserException();
        }

        $groupName = '';
        if (!empty($user['group_id'])) {
            $group = LinGroup::query()->where('id', $user['group_id'])->first(['name']);
            $groupName = $group['name'];
        }
        $user['group_name'] = $groupName;

        $auths = LinAuth::query()->where('group_id', $user['group_id'])
            ->get()->toArray();

        $auths = empty($auths) ? [] : split_modules($auths);

        $user['auths'] = $auths;

        return $user;
    }

    public static function updateUserAvatar($uid, $url)
    {
        $user = LinUser::query()->where('id', $uid)->first();
        if (!$user) {
            throw new UserException();
        }
        $user->avatar = $url;
        $user->save();
    }
}
