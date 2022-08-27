<?php

namespace App\Lib\Authenticator\Executor\Impl;

use App\Lib\Authenticator\Executor\IExecutor;

class GroupRequireExecutorImpl implements IExecutor
{

    public function handle(array $userInfo = null, string $permissionName = ''): bool
    {
        if (empty($userInfo['permissions'])) return false;

        $permissionArray = [];
        foreach ($userInfo['permissions'] as $permissionGroup) {
            foreach ($permissionGroup as $group) {
                foreach ($group as $permission) {

                    $permission = (array)$permission;
                    $permissionTag = $permission['permission'] . '/' . $permission['module'];
                    array_push($permissionArray, $permissionTag);
                }
            }
        }
        return in_array($permissionName, $permissionArray);
    }
}
