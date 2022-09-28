<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\V1\BookController;
use App\Http\Controllers\V1\OrganizationController;
use App\Http\Controllers\V1\TradeController;
use App\Http\Controllers\V1\OrderController;
use App\Http\Controllers\V1\ConnectController;
use App\Http\Controllers\V1\AccountController;
use App\Http\Controllers\V1\VerifyController;
use App\Http\Controllers\V1\ArticleController;

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
Route::prefix('test')->group(function () {
    Route::get('test', [TestController::class, 'test']);
    Route::get('test-login', [TestController::class, 'testLogin']);

});
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


Route::prefix('account')->group(function () {
    //
    Route::post('register', [AccountController::class, 'register']);
    Route::post('password/login', [AccountController::class, 'loginByPassword']);
    Route::post('verify/login', [AccountController::class, 'loginByVerify']);
    Route::get('logout', [AccountController::class, 'logout']);
    Route::post('password/reset', [AccountController::class, 'resetPassword']);
    Route::post('phone/update', [AccountController::class, 'updatePhone']);
    Route::post('email/update', [AccountController::class, 'updateEmail']);
    Route::post('password/update', [AccountController::class, 'updatePassword']);


});


Route::prefix('connect')->group(function () {
    //
    Route::get('qq', [ConnectController::class, 'qqLogin']);
    Route::get('weixin', [ConnectController::class, 'weixinLogin']);
    Route::get('weiboLogin', [ConnectController::class, 'weiboLogin']);

    Route::get('bind/login', [ConnectController::class, 'bindLogin']);
    Route::get('bind/register', [ConnectController::class, 'bindRegister']);
    Route::get('weiboLogin', [ConnectController::class, 'weiboLogin']);

});



Route::prefix('verify')->group(function () {
    //
    Route::post('sms/code', [VerifyController::class, 'smsCode']);
    Route::post('mail/code', [VerifyController::class, 'mailCode']);


});


Route::prefix('article')->group(function () {
    //
    Route::post('', [ArticleController::class, 'createArticle']);
    Route::put('{id}', [ArticleController::class, 'updateArticle']);
    Route::get('categories', [ArticleController::class, 'categories']);
    Route::get('', [ArticleController::class, 'getArticles']);
    Route::get('add', [ArticleController::class, 'getEnums']);
    Route::get('{id}', [ArticleController::class, 'getArticle']);
    Route::post('close/{id}', [ArticleController::class, 'closeArticle']);
    Route::post('private/{id}', [ArticleController::class, 'privateArticle']);
    Route::post('favorite/{id}', [ArticleController::class, 'favoriteArticle']);
    Route::post('like/{id}', [ArticleController::class, 'likeArticle']);


});
