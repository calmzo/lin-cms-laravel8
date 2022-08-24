<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\UserController;
use App\Http\Controllers\Cms\AdminController;
use App\Http\Controllers\Cms\LogController;
use App\Http\Controllers\Cms\FileController;

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

// 账户相关接口分组
Route::prefix('user')->group(function () {
    // 登陆接口
    Route::post('login', [UserController::class, 'login']);
    // 刷新令牌 todo
    Route::get('refresh', [UserController::class, 'refreshToken']);
    // 查询自己拥有的权限
    Route::get('auths', [UserController::class, 'getAllowedApis']);
    // 注册一个用户
    Route::post('register', [UserController::class, 'register']);
    // 更新头像
    Route::put('avatar',[UserController::class, 'setAvatar']);
    // 查询自己信息
    Route::get('information', [UserController::class, 'getInformation']);
    // 用户更新信息
    Route::put('', [UserController::class, 'update']);
    // 修改自己密码 todo
    Route::put('change_password', [UserController::class, 'changePassword']);
});
// 管理类接口
Route::prefix('admin')->group(function () {
    // 查询所有可分配的权限
    Route::get('permission', [AdminController::class, 'getAllPermissions']);
    // 查询所有用户
    Route::get('users', [AdminController::class, 'getAdminUsers']);
    // 修改用户密码
    Route::put('user/:id/password', [AdminController::class, 'changeUserPassword']);
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
    Route::put('group/{id}', [AdminController::class, 'deleteGroup']);
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



