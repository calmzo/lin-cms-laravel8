<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Utils\CodeResponse;
use App\VerifyRequestInpuit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    use VerifyRequestInpuit;
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

    protected function codeReturn(array $codeResponse, $data = null, $message = '')
    {
        list($code, $message) = $codeResponse;
        $ret = ['code' => $code];
        if (!is_null($data)) {
            $ret['result'] = $data;
        }
        $ret['message'] = $message ?: $message;
        return response()->json($ret);
    }

    protected function success($data = null, $message)
    {
        return $this->codeReturn(CodeResponse::SUCCESS, $data, $message);
    }

    protected function fail(array $codeResponse = CodeResponse::FAIL, $message = '')
    {
        return $this->codeReturn($codeResponse, null, $message);
    }

    protected function successPaginate($page)
    {
        return $this->success($this->paginate($page));
    }

    protected function paginate($page)
    {
        if ($page instanceof LengthAwarePaginator) {
            return [
                'total' => $page->total(),
                'page' => $page->currentPage(),
                'limit' => $page->perPage(),
                'pages' => $page->lastPage(),
                'list' => $page->items()
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
            'page' => 1,
            'limit' => $total,
            'pages' => 1,
            'list' => $page
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
