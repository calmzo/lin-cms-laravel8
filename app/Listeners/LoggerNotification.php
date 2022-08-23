<?php

namespace App\Listeners;

use App\Events\Logger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoggerNotification
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
     * Handle the event.
     *
     * @param  \App\Events\Logger  $event
     * @return void
     */
    public function handle(Logger $event)
    {
        $params = $event->params;
        if (is_array($params)) {
            list('uid' => $uid, 'username' => $username, 'msg' => $message) = $params;
        } else {
            $tokenService = LoginToken::getInstance();
            $uid = $tokenService->getCurrentUid();
            $username = $tokenService->getCurrentUserName();
            $message = $params;
        }

        $data = [
            'message' => $username . $message,
            'user_id' => $uid,
            'username' => $username,
            'status_code' => Response::getCode(),
            'method' => Request::method(),
            'path' => '/' . Request::path(),
            'permission' => null
        ];

        LinLog::create($data);



    }
}
