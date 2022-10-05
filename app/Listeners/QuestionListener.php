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


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\QuestionAfterViewEvent',
            [QuestionListener::class, 'afterView']
        );
    }
}
