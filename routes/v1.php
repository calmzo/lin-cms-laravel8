<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\BookController;
use App\Http\Controllers\V1\OrganizationController;
use App\Http\Controllers\V1\TradeController;
use App\Http\Controllers\V1\OrderController;

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

//组织架构
Route::prefix('organization')->group(function () {
    //
    Route::post('', [OrganizationController::class, 'createOrganization']);
    Route::get('/{id}', [OrganizationController::class, 'getOrganization']);
    Route::get('', [OrganizationController::class, 'getOrganizations']);
});


Route::prefix('trade')->group(function () {
    //
    Route::post('h5/create', [TradeController::class, 'createH5Trade']);
    Route::post('mini/create', [TradeController::class, 'createMiniTrade']);
    Route::post('qrcode/create', [TradeController::class, 'createQrcodeTrade']);
    Route::post('{id}/refund', [TradeController::class, 'refund']);

});

Route::prefix('order')->group(function () {
    //
    Route::post('create', [OrderController::class, 'createOrder']);
    Route::post('confirm', [OrderController::class, 'confirmOrder']);

});

