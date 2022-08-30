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


/**
 * 格式化字节大小
 * @param $size 字节数
 * @param string $delimiter 数字和单位分隔符
 * @return string 格式化后的带单位的大小
 */
function get_byte($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 生成随机字符串
 * @param $length  输出长度
 * @param string $chars 可选的 ，默认为 0123456789
 * @return string 字符串
 */
function get_random($length, $chars = '0123456789')
{
    $hash = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}


/**
 * 循环删除目录和文件函数
 * @param string $dirName 路径
 * @param boolean $fileFlag 是否删除目录
 * @return void
 */
function del_dir_file($dirName, $bFlag = false)
{
    if ($handle = opendir("$dirName")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dirName/$item")) {
                    del_dir_file("$dirName/$item", $bFlag);
                } else {
                    unlink("$dirName/$item");
                }
            }
        }
        closedir($handle);
        if ($bFlag) rmdir($dirName);
    }
}

/**
 *  作用：将xml转为array
 */
function xmlToArray($xml)
{
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}

/**
 * 获取文件信息
 * @param string $filepath 路径
 * @param string $key 指定返回某个键值信息
 * @return array
 */
function get_file_info($filepath = '', $key = '')
{
    //打开文件，r表示以只读方式打开
    $handle = fopen($filepath, "r");
    //获取文件的统计信息
    $fstat = fstat($handle);

    fclose($handle);
    $fstat['filename'] = basename($filepath);
    if (!empty($key)) {
        return $fstat[$key];
    } else {
        return $fstat;
    }
}

/**
 * 将一个字符串转换成数组，支持中文
 * @param string $string 待转换成数组的字符串
 * @return string   转换后的数组
 */
function strToArray($string)
{
    $strlen = mb_strlen($string);
    while ($strlen) {
        $array[] = mb_substr($string, 0, 1, "utf8");
        $string = mb_substr($string, 1, $strlen, "utf8");
        $strlen = mb_strlen($string);
    }
    return $array;
}

/**
 * 递归获取树
 * @param $data
 * @param int $parent_id
 * @return array
 */
function getTree($data, $parent_id = 0)
{
    $tree = [];
    foreach ($data as $k => $v) {
        if ($v['parent_id'] == $parent_id) {
            $v['children'] = getTree($data, $v['id']);
            $tree[] = $v;
            unset($data[$k]);
        }
    }
    return $tree;
}

