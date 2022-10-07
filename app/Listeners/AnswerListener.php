<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AnswerListener
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

    public function afterCreate($event)
    {
        Log::channel('answer')->info('监听答案创建事件');
    }

    public function afterUpdate($event)
    {
        Log::channel('answer')->info('监听答案修改事件');
    }

    public function afterDelete($event)
    {
        Log::channel('answer')->info('监听答案删除事件');
    }

    public function afterAccept($event)
    {
        Log::channel('answer')->info('监听答案采纳事件');
    }

    public function afterUndoAccept($event)
    {
        Log::channel('answer')->info('监听答案取消采纳事件');
    }

    public function afterLike($event)
    {
        Log::channel('answer')->info('监听答案点赞事件');
    }

    public function afterUndoLike($event)
    {
        Log::channel('answer')->info('监听答案取消点赞事件');
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\AnswerAfterCreateEvent',
            [AnswerListener::class, 'afterCreate']
        );
        $events->listen(
            'App\Events\AnswerAfterUpdateEvent',
            [AnswerListener::class, 'afterUpdate']
        );
        $events->listen(
            'App\Events\AnswerAfterAcceptEvent',
            [AnswerListener::class, 'afterAccept']
        );
        $events->listen(
            'App\Events\AnswerAfterUndoAcceptEvent',
            [AnswerListener::class, 'afterUndoAccept']
        );
        $events->listen(
            'App\Events\AnswerAfterLikeEvent',
            [AnswerListener::class, 'afterLike']
        );
        $events->listen(
            'App\Events\AnswerAfterUndoLikeEvent',
            [AnswerListener::class, 'afterUndoLike']
        );
    }
}
