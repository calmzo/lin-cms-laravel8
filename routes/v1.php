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
use App\Http\Controllers\V1\ReportController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\UserConsoleController;
use App\Http\Controllers\V1\ReviewController;
use App\Http\Controllers\V1\QuestionController;
use App\Http\Controllers\V1\IndexController;
use App\Http\Controllers\V1\LiveController;
use App\Http\Controllers\V1\HelpController;
use App\Http\Controllers\V1\CourseController;
use App\Http\Controllers\V1\AnswerController;
use App\Http\Controllers\V1\ChapterController;
use App\Http\Controllers\V1\CommentController;
use App\Http\Controllers\V1\FlashSaleController;
use App\Http\Controllers\V1\PageController;
use App\Http\Controllers\V1\RefundController;

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
    // todo
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
    Route::delete('{id}', [ArticleController::class, 'deleteArticle']);
    Route::get('categories', [ArticleController::class, 'categories']);
    Route::get('', [ArticleController::class, 'getArticles']);
    Route::get('add', [ArticleController::class, 'getEnums']);
    Route::get('{id}', [ArticleController::class, 'getArticle']);
    Route::post('close/{id}', [ArticleController::class, 'closeArticle']);
    Route::post('private/{id}', [ArticleController::class, 'privateArticle']);
    Route::post('favorite/{id}', [ArticleController::class, 'favoriteArticle']);
    Route::post('like/{id}', [ArticleController::class, 'likeArticle']);


});


Route::prefix('report')->group(function () {
    //
    Route::post('', [ReportController::class, 'createReport']);


});


Route::prefix('user')->group(function () {
    //
    Route::get('{id}/info', [UserController::class, 'getUser']);
    Route::get('{id}/articles', [UserController::class, 'getArticles']);
    Route::get('{id}/questions', [UserController::class, 'getQuestions']);
    Route::get('{id}/answers', [UserController::class, 'getAnswers']);


});


Route::prefix('uc')->group(function () {
    //
    Route::get('profile', [UserConsoleController::class, 'getUserConsole']);
    Route::get('account', [UserConsoleController::class, 'getUserAccount']);
    Route::get('articles', [UserConsoleController::class, 'getUserArticles']);
    Route::get('questions', [UserConsoleController::class, 'getUserQuestions']);
    Route::get('answers', [UserConsoleController::class, 'getAnswers']);
    Route::get('favorites', [UserConsoleController::class, 'getFavorites']);
    Route::get('consults', [UserConsoleController::class, 'getConsults']);
    Route::get('reviews', [UserConsoleController::class, 'getReviews']);
    Route::get('orders', [UserConsoleController::class, 'getOrders']);
    Route::get('refunds', [UserConsoleController::class, 'getRefunds']);
    Route::get('notifications', [UserConsoleController::class, 'getNotifications']);
    Route::get('notify-stats', [UserConsoleController::class, 'getNotifyStats']);
    Route::put('profile', [UserConsoleController::class, 'updateProfile']);
    Route::post('online', [UserConsoleController::class, 'online']);


});

Route::prefix('review')->group(function () {
    //
    Route::get('{id}/info', [ReviewController::class, 'getReview']);
    Route::post('', [ReviewController::class, 'createReview']);
    Route::put('{id}', [ReviewController::class, 'updateReview']);
    Route::delete('{id}', [ReviewController::class, 'deleteReview']);
    Route::post('like/{id}', [ReviewController::class, 'likeReview']);
});

Route::prefix('question')->group(function () {
    //
    Route::get('{id}/info', [QuestionController::class, 'getQuestion']);
    Route::get('', [QuestionController::class, 'getQuestions']);
    Route::get('categories', [QuestionController::class, 'getCategories']);
    Route::get('{id}/answers', [QuestionController::class, 'getAnswers']);
    Route::get('{id}/comments', [QuestionController::class, 'getComments']);
    Route::delete('{id}', [QuestionController::class, 'deleteQuestion']);
    Route::post('{id}/favorite', [QuestionController::class, 'favoriteQuestion']);
    Route::post('{id}/like', [QuestionController::class, 'likeQuestion']);

});


Route::prefix('index')->group(function () {
    //
    Route::get('slides', [IndexController::class, 'getSlides']);
    Route::get('articles', [IndexController::class, 'getArticles']);
    Route::get('questions', [IndexController::class, 'getQuestions']);
    Route::get('lives', [IndexController::class, 'getLives']);
    Route::get('teachers', [IndexController::class, 'getTeachers']);
    Route::get('flash/sales', [IndexController::class, 'getFalshSales']);
    Route::get('courses/featured', [IndexController::class, 'getFeaturedCourses']);
    Route::get('courses/new', [IndexController::class, 'getNewCourses']);
    Route::get('courses/free', [IndexController::class, 'getFreeCourses']);
    Route::get('courses/vip', [IndexController::class, 'getVipCourses']);

});

Route::prefix('live')->group(function () {
    //
    Route::get('', [LiveController::class, 'getLives']);
    Route::get('{id}/chats', [LiveController::class, 'getLiveChats']);
    Route::get('{id}/stats', [LiveController::class, 'getLiveStats']);
    Route::get('{id}/status', [LiveController::class, 'getLiveStatus']);
    Route::post('{id}/user/bind', [LiveController::class, 'bindUser']);
    Route::post('{id}/msg/send', [LiveController::class, 'sendMsg']);
});

Route::prefix('help')->group(function () {
    //
    Route::get('list', [HelpController::class, 'getHelps']);
    Route::get('{id}/info', [HelpController::class, 'getHelp']);
});


Route::prefix('course')->group(function () {
    //
    Route::get('list', [CourseController::class, 'getCourses']);
    Route::get('{id}/info', [CourseController::class, 'getCourse']);
    Route::get('categories', [CourseController::class, 'getCategories']);
    Route::get('{id}/chapters', [CourseController::class, 'getCourseChapters']);
    Route::get('{id}/packages', [CourseController::class, 'getCoursePackages']);
    Route::get('{id}/consults', [CourseController::class, 'getCourseConsults']);
    Route::get('{id}/reviews', [CourseController::class, 'getCourseReviews']);
    Route::post('{id}/favorite', [CourseController::class, 'favoriteCourse']);
});

Route::prefix('answer')->group(function () {
    //
    Route::get('{id}/info', [AnswerController::class, 'getAnswer']);
    Route::post('create', [AnswerController::class, 'createAnswer']);
    Route::get('{id}/comments', [AnswerController::class, 'getComments']);
    Route::put('{id}/update', [AnswerController::class, 'updateAnswer']);
    Route::delete('{id}/delete', [AnswerController::class, 'deleteAnswer']);
    Route::post('{id}/accept', [AnswerController::class, 'acceptAnswer']);
    Route::post('{id}/like', [AnswerController::class, 'likeAnswer']);

});

Route::prefix('chapter')->group(function () {
    //
    Route::get('{id}/comments', [ChapterController::class, 'getComments']);
    Route::get('{id}/consults', [ChapterController::class, 'getConsults']);
    Route::get('{id}/resources', [ChapterController::class, 'getResources']);
    Route::get('{id}/info', [ChapterController::class, 'getChapter']);
    Route::post('{id}/like', [ChapterController::class, 'likeChapter']);
    Route::post('{id}/learning', [ChapterController::class, 'learningChapter']);

});

Route::prefix('comment')->group(function () {
    //
    Route::get('{id}/replies', [CommentController::class, 'getReplies']);
    Route::get('{id}/info', [CommentController::class, 'getComment']);
    Route::post('create', [CommentController::class, 'createComment']);
    Route::post('{id}/reply', [CommentController::class, 'replyComment']);
    Route::delete('{id}/delete', [CommentController::class, 'deleteComment']);
    Route::post('{id}/like', [CommentController::class, 'likeComment']);

});

Route::prefix('flash/sale')->group(function () {
    //
    Route::get('list', [FlashSaleController::class, 'getFlashSales']);
    Route::post('order', [FlashSaleController::class, 'createOrder']);

});

Route::prefix('page')->group(function () {
    //
    Route::get('{id}/info', [PageController::class, 'getPage']);

});

Route::prefix('refund')->group(function () {
    //
    Route::get('confirm', [RefundController::class, 'getConfirm']);
    Route::get('info', [RefundController::class, 'getRefund']);

});
