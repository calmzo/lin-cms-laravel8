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


    public function handleRegisterPoint($event)
    {
        $service = new AccountRegister();

        $service->handle($event->user);
    }

    public function handleLoginNotice($event)
    {

        $service = new AccountLogin();

        $service->createTask($event->user);
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
    }
}
