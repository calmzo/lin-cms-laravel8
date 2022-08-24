<?php

namespace App\Services\Admin;


use App\Enums\GroupLevelEnums;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\AuthFailedException;
use App\Exceptions\OperationException;
use App\Exceptions\RepeatException;
use App\Models\Admin\LinGroup;
use App\Models\Admin\LinUser;
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
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ForbiddenException
     * @throws ModelNotFoundException
     * @throws NotFoundException
     * @throws OperationException
     * @throws RepeatException
     */
    public static function createUser(array $params): LinUserModel
    {
        $user = LinUserModel::query()->where('username', $params['username'])->first();
        if ($user) {
            throw new RepeatException(CodeResponse::REPEAT, '用户名已存在');
        }
        if (isset($params['email'])) {
            $user = LinUserModel::query()->where('email', $params['email'])->first();
            if ($user) {
                throw new RepeatException(CodeResponse::REPEAT, '邮箱地址已存在');
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
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws NotFoundException
     */
    public static function verify(string $username, string $password): Model
    {
        $user = self::getByUsername($username);
        if (empty($user)) {
            throw new NotFoundException(CodeResponse::ACCOUNT_NOT_FOUND, '用户不存在');
        }
        //验证密码
        $is_pass = Hash::check($password, $user->password);
        if (!$is_pass) {
            throw new NotFoundException(CodeResponse::PASSWORD_WRONG, '密码错误，请重新输入');
//            throw new AuthFailedException();
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
        $user = LinUserModel::get($uid);

        $groupIds = LinUserGroupModel::where('user_id', $uid)
            ->column('group_id');

        $root = LinGroupModel::where('level', GroupLevelEnum::ROOT)
            ->whereIn('id', $groupIds)->find();

        $user = $user->hidden(['username'])->toArray();
        $user['admin'] = $root ? true : false;

        if ($root) {
            $permissions = LinPermissionModel::where('mount', MountTypeEnum::MOUNT)
                ->select()
                ->toArray();
            $user['permissions'] = formatPermissions($permissions);
        } else {
            $permissionIds = LinGroupPermissionModel::whereIn('group_id', $groupIds)
                ->column('permission_id');
            $permissions = LinPermissionModel::where('mount', MountTypeEnum::MOUNT)
                ->select($permissionIds)->toArray();

            $user['permissions'] = formatPermissions($permissions);

        }

        return $user;
    }

    public static function getInformation(int $uid)
    {
        return LinUser::get($uid, 'groups');
    }

    public static function updateUser(array $params): int
    {
        $user = LoginToken::getInstance()->getTokenExtend();
        if (isset($params['username']) && $params['username'] !== $user['username']) {
            $isExit = LinUserModel::where('username', $params['username'])
                ->find();
            if ($isExit) {
                throw new RepeatException(['msg' => "用户名已被占用"]);
            }
        }

        if (isset($params['email']) && $params['email'] !== $user['email']) {
            $isExit = LinUserModel::where('email', $params['email'])
                ->find();
            if ($isExit) {
                throw new RepeatException(['msg' => "邮箱已被占用"]);
            }
        }

        $user = LinUserModel::get($user['id']);
        return $user->allowField(true)->save($params);
    }

    public static function changePassword(string $oldPassword, string $newPassword): int
    {
        $currentUser = LoginToken::getInstance()->getTokenExtend();
        $user = new LinUserIdentityModel();

        $user = $user::where('identity_type', IdentityTypeEnum::PASSWORD)
            ->where('identifier', $currentUser['identifier'])
            ->find();

        if (!$user) {
            throw new NotFoundException();
        }

        if (!$user->checkPassword($oldPassword)) {
            throw new AuthFailedException();
        }

        $user->credential = md5($newPassword);
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
//            $user->identity()->save([
//                'identity_type' => IdentityTypeEnum::PASSWORD,
//                'identifier' => $user['username'],
//                'credential' => md5($params['password'])
//            ]);
            $user = new LinUser();
            $user->username = $params['username'];
            $user->password = Hash::make($params['password']);
            $user->email = $params['email'];
            $user->avatar = 'https://yanxuan.nosdn.127.net/80841d741d7fa3073e0ae27bf487339f.jpg?imageView&quality=90&thumbnail=64x64';
            $user->nickname = $params['username'];
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

    // private static function formatPermissions(array $permissions)
    // {
    //     $groupPermission = [];
    //     foreach ($permissions as $permission) {
    //         $item = [
    //             'name' => $permission['name'],
    //             'module' => $permission['module']
    //         ];
    //         $groupPermission[$permission['module']][] = $item;
    //     }
    //
    //     $result[] = array_map(function ($item) {
    //         return $item;
    //     }, $groupPermission);
    //     return $result;
    // }

    public static function aa(){
        $user = UserServices::getInstance()->getByUsername($username);
        return $user;
    }

    /**
     * 根据用户名获取用户
     * @param  string  $username
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByUsername(string $username)
    {
        return LinUserModel::query()->where('username', $username)->first();
    }
}
