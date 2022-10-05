<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class QuestionListener
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

    public function afterView($event)
    {
        Log::channel('question')->info('监听问题查看事件');
    }

    public function afterDelete($event)
    {
        Log::channel('question')->info('监听问题删除事件');
    }

    public function afterFavorite($event)
    {
        Log::channel('question')->info('监听收藏删除事件');
    }

    public function afterUndoFavorite($event)
    {
        Log::channel('question')->info('监听取消收藏事件');
    }

    public function afterLike($event)
    {
        Log::channel('question')->info('监听点赞事件');
    }

    public function afterUndoLike($event)
    {
        Log::channel('question')->info('监听取消点赞事件');
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\QuestionAfterViewEvent',
            [QuestionListener::class, 'afterView']
        );

        $events->listen(
            'App\Events\QuestionAfterDeleteEvent',
            [QuestionListener::class, 'afterDelete']
        );

        $events->listen(
            'App\Events\QuestionAfterFavoriteEvent',
            [QuestionListener::class, 'afterFavorite']
        );

        $events->listen(
            'App\Events\QuestionAfterUndoFavoriteEvent',
            [QuestionListener::class, 'afterUndoFavorite']
        );

        $events->listen(
            'App\Events\QuestionAfterLikeEvent',
            [QuestionListener::class, 'afterLike']
        );

        $events->listen(
            'App\Events\QuestionAfterUndoLikeEvent',
            [QuestionListener::class, 'afterUndoLike']
        );


    }
}
