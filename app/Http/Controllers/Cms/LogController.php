<?php

namespace App\Http\Controllers\Cms;

use App\Services\Admin\LogService;
use App\Validates\Log\LogListValidate;
use App\Validates\Log\LogSearchValidate;
use LinCmsTp5\exception\ParameterException;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    protected $except = [];


    /**
     * @groupRequired
     * @permission('查询所有日志','日志')
     * @param LogListValidate $logListValidate
     * @return array|mixed
     * @throws ParameterException
     * @throws \App\Exceptions\ValidateException
     */
    public function getLogs(LogListValidate $logListValidate)
    {
        $params = $logListValidate->check();
        $start = $params['start'] ?? null;
        $end = $params['end'] ?? null;
        $name = $params['name'] ?? null;
        $page = $params['page'] ?? 0; //分页数
        $count = $params['count'] ?? 10; //分页值
        return $this->successPaginate(LogService::getLogs($page, $count, $start, $end, $name));
    }

    /**
     * @groupRequired
     * @permission('搜索日志','日志')
     * @param LogSearchValidate $logSearchValidate
     * @return array|mixed
     * @throws ParameterException
     * @throws \App\Exceptions\ValidateException
     */
    public function getUserLogs(LogSearchValidate $logSearchValidate)
    {
        $params = $logSearchValidate->check();
        $start = $params['start'] ?? null;
        $end = $params['end'] ?? null;
        $name = $params['name'] ?? null;
        $keyword = $params['keyword'] ?? null;
        $page = $params['page'] ?? 0; //分页数
        $count = $params['count'] ?? 10; //分页值
        return $this->successPaginate(LogService::searchLogs($page, $count, $start, $end, $name, $keyword));
    }

    /**
     * @groupRequired
     * @permission('查询日志记录的用户','日志')
     * @param Request $request
     * @return array|mixed
     * @throws ParameterException
     */
    public function getUsers(Request $request)
    {
        $page = $request->input('page', 0);
        $count = $request->input('count', 10);
        $list = LogService::getUserNames($page, $count);
        return $this->successPaginate($list);
    }
}
