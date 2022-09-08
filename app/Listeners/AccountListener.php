<?php

namespace App\Listeners;

use App\Lib\Notice\AccountLogin;
use App\Services\Logic\Point\History\AccountRegister;

class AccountListener
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
     * 注册加积分
     * @param $event
     */
    public function handleRegisterPoint($event)
    {
        $service = new AccountRegister();

        $service->handle($event->user);
    }

    //登录通知
    public function handleLoginNotice($event)
    {

        $service = new AccountLogin();

        $service->createTask($event->user);
    }


    //todo 退出登录
    public function handleLogoutNotice($event)
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
            'App\Events\AccountRegisterEvent',
            [AccountListener::class, 'handleRegisterPoint']
        );

        $events->listen(
            'App\Events\AccountLoginEvent',
            [AccountListener::class, 'handleLoginNotice']
        );

        $events->listen(
            'App\Events\AccountLogoutEvent',
            [AccountListener::class, 'handleLogoutNotice']
        );
    }
}
