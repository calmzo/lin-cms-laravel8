<?php

namespace App\Http\Controllers\V1;

use App\Events\Logger;
use App\Http\Controllers\Cms\BaseController;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends BaseController
{
    protected $except = [];

    /**
     * @param $bid
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getBook($bid)
    {
        $result = BookService::getBook($bid);
        return $result;
    }

    /**
     * 查询所有图书
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getBooks()
    {
        $result = BookService::getBooks();
        return $result;
    }

    /**
     * 搜索图书
     */
    public function search()
    {

    }

    /**
     * 新建图书
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function createBook(Request $request)
    {
        $params = $request->all();
        BookService::createBook($params);
        return $this->success([], '新建图书成功');
    }

    /**
     * 编辑图书
     * @param Request $request
     * @param $bid
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\OperationException
     */
    public function updateBook(Request $request, $bid)
    {
        $params = $request->all();
        BookService::updateBook($bid, $params);
        return $this->success([], '更新图书成功');
    }

    /**
     * @groupRequired
     * @permission('删除图书','图书')
     * @param $bid
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function deleteBook($bid)
    {
        BookService::deleteBook($bid);
        Logger::dispatch("删除了id为{$bid}的图书");
        return $this->success([], '删除图书成功');
    }
}
