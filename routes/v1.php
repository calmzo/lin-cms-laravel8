<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\BookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 图书相关接口分组
Route::prefix('book')->group(function () {
    // 查询所有图书
    Route::get('', [BookController::class, 'getBooks']);
    // 新建图书
    Route::post('', [BookController::class, 'createBook']);
    // 查询指定bid的图书
    Route::get('/{bid}', [BookController::class, 'getBook']);
    // 搜索图书

    // 更新图书
    Route::put('/{bid}', [BookController::class, 'updateBook']);
    // 删除图书
    Route::delete('/{bid}', [BookController::class, 'deleteBook']);
});



