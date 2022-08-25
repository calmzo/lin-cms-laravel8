<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Utils\CodeResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected $only;
    protected $except;

    /**
     * WxController constructor.
     */
    public function __construct()
    {
        $options = [];
        if (!is_null($this->only)) {
            $options['only'] = $this->only;
        }
        if (!is_null($this->except)) {
            $options['except'] = $this->except;
        }
        $this->middleware('auth:cms', $options);
    }

    protected function codeReturn(array $codeResponse, $data = null, $info = '')
    {
        list($code, $message) = $codeResponse;
        $ret = ['code' => $code];
        if (!is_null($data)) {
            $ret['result'] = $data;
        }
        $ret['message'] = $info ?: $message;
        return response()->json($ret);
    }

    protected function success($data = null, $info = '成功')
    {
        list($code, $message) = CodeResponse::SUCCESS;
        $ret = ['code' => $code];
        if (!is_null($data)) {
            $ret['result'] = $data;
        }
        $ret['msg'] = $info ?: $message;
        return response()->json($ret);
    }

    protected function fail(array $codeResponse = CodeResponse::FAIL, $message = '')
    {
        return $this->codeReturn($codeResponse, null, $message);
    }

    protected function successPaginate($page)
    {
//        return $this->success($this->paginate($page));
        return $this->paginate($page);
    }

    protected function paginate($page)
    {
        if ($page instanceof LengthAwarePaginator) {
            return [
                'total' => $page->total(),
                'page' => $page->currentPage() - 1,
                'count' => $page->perPage(),
                'items' => $page->items()
            ];
        }
        if ($page instanceof Collection) {
            $page = $page->toArray();
        }
        if (!is_array($page)) {
            return $page;
        }
        $total = count($page);
        return [
            'total' => $total,
            'page' => 0,
            'limit' => $total,
            'items' => $page
        ];

        return $page;

    }

    public function user()
    {
        return Auth::guard('cms')->user();
    }


    public function isLogin()
    {
        return !is_null($this->user());
    }

    public function userId()
    {
        return $this->user()->getAuthIdentifier();
    }
}
