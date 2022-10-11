<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CommentListener
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
        Log::channel('comment')->info('监听评论创建事件');
    }

    public function afterReply($event)
    {
        Log::channel('comment')->info('监听评论回复事件');
    }

    public function afterDelete($event)
    {
        Log::channel('comment')->info('监听评论删除事件');
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\CommentAfterCreateEvent',
            [CommentListener::class, 'afterCreate']
        );

        $events->listen(
            'App\Events\CommentAfterReplyEvent',
            [CommentListener::class, 'afterReply']
        );

        $events->listen(
            'App\Events\CommentAfterDeleteEvent',
            [CommentListener::class, 'afterDelete']
        );
    }
}
