<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\UserController;
use App\Http\Controllers\Cms\AdminController;
use App\Http\Controllers\Cms\LogController;
use App\Http\Controllers\Cms\FileController;
use App\Http\Controllers\Cms\UploadController;
use App\Http\Controllers\Cms\RefundController;
use App\Http\Controllers\Cms\IndexController;
use App\Http\Controllers\Cms\CourseController;
use App\Http\Controllers\Cms\TagController;
use App\Http\Controllers\Cms\ArticleController;
use App\Http\Controllers\Cms\ReportController;

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


Route::prefix('index')->group(function () {
    Route::get('main', [IndexController::class, 'main']);
});

// 账户相关接口分组
Route::prefix('user')->group(function () {
    // 登陆接口
    Route::post('login', [UserController::class, 'login']);
    // 刷新令牌 todo
    Route::get('refresh', [UserController::class, 'refreshToken']);
    // 查询自己拥有的权限
    Route::get('permissions', [UserController::class, 'getAllowedApis']);
    // 注册一个用户
    Route::post('register', [UserController::class, 'register']);
    // 查询自己信息
    Route::get('information', [UserController::class, 'getInformation']);
    // 用户更新信息
    Route::put('', [UserController::class, 'update']);
    // 修改自己密码
    Route::put('change_password', [UserController::class, 'changePassword']);
});
// 管理类接口
Route::prefix('admin')->group(function () {
    // 查询所有可分配的权限
    Route::get('permission', [AdminController::class, 'getAllPermissions']);
    // 查询所有用户
    Route::get('users', [AdminController::class, 'getAdminUsers']);
    // 修改用户密码
    Route::put('user/{id}/password', [AdminController::class, 'changeUserPassword']);
    // 删除用户
    Route::delete('user/{id}', [AdminController::class, 'deleteUser']);
    // 更新用户信息
    Route::put('user/{id}', [AdminController::class, 'updateUser']);
    // 查询所有权限组
    Route::get('group/all', [AdminController::class, 'getGroupAll']);
    // 新增权限组
    Route::post('group', [AdminController::class, 'createGroup']);
    // 查询指定分组及其权限
    Route::get('group/{id}', [AdminController::class, 'getGroup']);
    // 更新一个权限组
    Route::put('group/{id}', [AdminController::class, 'updateGroup']);
    // 删除一个分组
    Route::delete('group/{id}', [AdminController::class, 'deleteGroup']);
    // 删除多个权限
    Route::post('permission/remove', [AdminController::class, 'removePermissions']);
    // 分配多个权限
    Route::post('permission/dispatch/batch', [AdminController::class, 'dispatchPermissions']);

});
// 日志类接口
Route::prefix('log')->group(function () {
    Route::get('', [LogController::class, 'getLogs']);
    Route::get('users', [LogController::class, 'getUsers']);
    Route::get('search', [LogController::class, 'getUserLogs']);
});
//上传文件类接口
Route::post('file', [FileController::class, 'postFile']);

// 日志类接口
Route::prefix('upload')->group(function () {
    Route::get('void/sign', [UploadController::class, 'getVoidSign']);
});

Route::prefix('refund')->group(function () {
    Route::post('{id}/review', [RefundController::class, 'review']);
});

Route::prefix('course')->group(function () {
    Route::get('category', [CourseController::class, 'category']);
    Route::get('search', [CourseController::class, 'search']);
    Route::get('', [CourseController::class, 'list']);
    Route::post('', [CourseController::class, 'create']);
    Route::get('add', [CourseController::class, 'add']);
});

Route::prefix('tag')->group(function () {
    Route::get('', [TagController::class, 'getTags']);
    Route::get('search', [TagController::class, 'searchTags']);
    Route::post('', [TagController::class, 'createTag']);
});

Route::prefix('article')->group(function () {
    Route::get('', [ArticleController::class, 'getArticles']);
    Route::get('search', [ArticleController::class, 'searchArticles']);
    Route::post('', [ArticleController::class, 'createArticle']);
});

Route::prefix('report')->group(function () {
    Route::get('articles', [ReportController::class, 'getArticles']);
    Route::get('questions', [ReportController::class, 'getQuestions']);
});



