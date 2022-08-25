<?php

namespace App\Http\Controllers\Cms;

use App\Services\Admin\LogService;
use LinCmsTp5\exception\ParameterException;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    protected $only = [];


    /**
     * @groupRequired
     * @permission('查询所有日志','日志')
     * @param Request $request
     * @param('page','分页数','integer')
     * @param('count','分页值','integer')
     * @param('start','开始日期','date')
     * @param('end','结束日期','date')
     * @return array
     * @throws ParameterException
     */
    public function getLogs(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $name = $request->input('name');
        $page = $request->input('page', 0);
        $count = $request->input('count', 10);

        return $this->successPaginate(LogService::getLogs($page, $count, $start, $end, $name));
    }

    /**
     * @groupRequired
     * @permission('搜索日志','日志')
     * @param Request $request
     * @param('page','分页数','integer')
     * @param('count','分页值','integer')
     * @param('start','开始日期','date')
     * @param('end','结束日期','date')
     * @return array
     * @throws ParameterException
     */
    public function getUserLogs(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $name = $request->get('name');
        $keyword = $request->get('keyword');
        $page = $request->get('page', 0);
        $count = $request->get('count', 10);

        return $this->successPaginate(LogService::searchLogs($page, $count, $start, $end, $name, $keyword));
    }

    /**
     * @groupRequired
     * @permission('查询日志记录的用户','日志')
     * @param Request $request
     * @param('page','分页数','integer')
     * @param('count','分页值','integer')
     * @return array
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
