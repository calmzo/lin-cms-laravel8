<?php

namespace App\Listeners;

use App\Services\Logic\Point\History\AccountRegister;

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

    }


    /**
     * 查看
     * @param $event
     */
    public function afterView($event)
    {

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
            [AccountListener::class, 'afterCreate']
        );

        $events->listen(
            'App\Events\ArticleAfterViewEvent',
            [AccountListener::class, 'afterView']
        );

    }
}
