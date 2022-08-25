<?php

namespace App\Services\Admin;

use app\api\model\admin\LinLog as LinLogModel;
use App\Models\Admin\LinLog;
use Illuminate\Pagination\LengthAwarePaginator;
use LinCmsTp5\exception\ParameterException;

class LogService
{
    /**
     * @param int $page
     * @param int $count
     * @param string|null $start
     * @param string|null $end
     * @param string|null $name
     * @return array
     * @throws ParameterException
     */
    public static function getLogs(int $page, int $count, string $start = null, string $end = null, string $name = null)
    {
        list($page, $count) = paginate($page, $count);
        $query = LinLog::query();
        if ($name) {
            $query->where('username', 'like', '%' . $name . '%');
        }
        if ($start && $end) {
            $query->whereBetween('create_time', [$start, $end]);
        }
        $res = $query->orderByDesc('create_time')->paginate($count, ['*'], 'page', $page);
        return $res;
    }

    /**
     * @param int $page
     * @param int $count
     * @param string|null $start
     * @param string|null $end
     * @param string|null $name
     * @param string|null $keyword
     * @return array
     * @throws ParameterException
     */
    public static function searchLogs(int $page, int $count, string $start = null,
                                      string $end = null, string $name = null, string $keyword = null)
    {
        list($page, $count) = paginate($page, $count);
        $query = LinLog::query();
        if ($name) {
            $query->where('username', 'like', '%' . $name . '%');
        }
        if ($start && $end) {
            $query->whereBetween('create_time', [$start, $end]);
        }

        if ($keyword) {
            $query->where('message', 'like', '%' . $keyword . '%');
        }

        $res = $query->orderByDesc('create_time')->paginate($count, ['*'], 'page', $page);
        return $res;
    }

    /**
     * @param int $page
     * @param int $count
     * @return array
     * @throws ParameterException
     */
    public static function getUserNames(int $page, int $count)
    {
        list($page, $count) = paginate($page, $count);
        $list = LinLog::query()->groupBy('username')->get(['username'])->pluck('username')->toArray();
        return paginator($list, $page, $count);
    }
}
