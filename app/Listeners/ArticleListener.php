<?php

namespace App\Listeners;

use App\Services\Logic\Point\History\AccountRegister;
use Illuminate\Support\Facades\Log;

class ArticleListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 添加文章加积分
     * @param $event
     */
    public function afterCreate($event)
    {
        Log::channel('article')->info('监听发布文章事件');
    }

    public function afterUpdate($event)
    {
        Log::channel('article')->info('监听修改文章事件');
    }

    public function afterDelete($event)
    {
        Log::channel('article')->info('监听删除文章事件');
    }


    /**
     * 查看
     * @param $event
     */
    public function afterView($event)
    {
        Log::channel('article')->info('监听查看文章事件');
    }

    public function afterFavorite($event)
    {
        Log::channel('article')->info('监听收藏文章事件');
    }

    public function afterUndoFavorite($event)
    {
        Log::channel('article')->info('监听取消收藏文章事件');
    }

    public function afterLike($event)
    {
        Log::channel('article')->info('监听点赞文章事件');
    }

    public function afterUndoLike($event)
    {
        Log::channel('article')->info('监听取消点赞文章事件');
    }


    /**
     * 为事件订阅者注册监听器
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ArticleAfterCreateEvent',
            [ArticleListener::class, 'afterCreate']
        );

        $events->listen(
            'App\Events\ArticleAfterUpdateEvent',
            [ArticleListener::class, 'afterUpdate']
        );

        $events->listen(
            'App\Events\ArticleAfterDeleteEvent',
            [ArticleListener::class, 'afterDelete']
        );

        $events->listen(
            'App\Events\ArticleAfterViewEvent',
            [ArticleListener::class, 'afterView']
        );

        $events->listen(
            'App\Events\ArticleAfterFavoriteEvent',
            [ArticleListener::class, 'afterFavorite']
        );

        $events->listen(
            'App\Events\ArticleAfterUndoFavoriteEvent',
            [ArticleListener::class, 'afterUndoFavorite']
        );

        $events->listen(
            'App\Events\ArticleAfterLikeEvent',
            [ArticleListener::class, 'afterLike']
        );

        $events->listen(
            'App\Events\ArticleAfterUndoLikeEvent',
            [ArticleListener::class, 'afterUndoLike']
        );

    }
}
