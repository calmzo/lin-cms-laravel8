<?php

namespace App\Listeners;

use App\Services\Logic\Point\History\AccountRegister;
use Illuminate\Support\Facades\Log;

class ReportListener
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
        Log::channel('report')->info('监听举报事件');
    }


    /**
     * 为事件订阅者注册监听器
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ReportAfterCreateEvent',
            [ReportListener::class, 'afterCreate']
        );

    }
}
