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
            'App\Events\AnswerAfterDeleteEvent',
            [AnswerListener::class, 'afterDelete']
        );
    }
}
