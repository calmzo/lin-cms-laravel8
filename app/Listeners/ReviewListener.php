<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ReviewListener
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
        Log::channel('review')->info('监听添加事件');
    }

    public function afterDelete($event)
    {
        Log::channel('review')->info('监听删除事件');
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ReviewAfterCreateEvent',
            [ReviewListener::class, 'afterCreate']
        );
        $events->listen(
            'App\Events\ReviewAfterDeleteEvent',
            [ReviewListener::class, 'afterDelete']
        );

    }
}
