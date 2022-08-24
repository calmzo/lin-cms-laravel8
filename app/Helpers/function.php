<?php

/**
 * 权限数组格式化函数
 * @param array $permissions
 * @return array
 */
function formatPermissions(array $permissions)
{
    $groupPermission = [];
    foreach ($permissions as $permission) {
        $item = [
            'permission' => $permission['name'],
            'module' => $permission['module']
        ];
        $groupPermission[$permission['module']][] = $item;
    }
    $result = [];
    foreach ($groupPermission as $key => $item) {
        array_push($result, [$key => $item]);
    }

    return $result;
}
