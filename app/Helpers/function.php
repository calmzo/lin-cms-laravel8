<?php

use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\ParameterException;

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


function split_modules($auths, $key = 'module')
{
    if (empty($auths)) {
        return [];
    }

    $items = [];
    $result = [];

    foreach ($auths as $key => $value) {
        if (isset($items[$value['module']])) {
            $items[$value['module']][] = $value;
        } else {
            $items[$value['module']] = [$value];
        }
    }
    foreach ($items as $key => $value) {
        $item = [
            $key => $value
        ];
        array_push($result, $item);
    }
    return $result;

}

function paginator($list, $page = 1, $count = 10)
{
    //分页
    $offset = ($page * $count) - $count;
    //实例化LengthAwarePaginator类，并传入对应的参数
    $paginator = new LengthAwarePaginator(array_slice($list, $offset, $count, true), count($list), $count, $page);
    return $paginator;
}


/**
 * 分页参数处理函数
 * @param int $page
 * @param int $count
 * @return int[]
 */
function paginate(int $page = 0, int $count = 10)
{
//    $count = $count >= 15 ? 15 : $count;
//    $start = $page * $count;
//
//    if ($start < 0 || $count < 0) throw new ParameterException();
    $count = $count >= 15 ? 15 : $count;
    $page = $page + 1;
    return [$page, $count];
}
