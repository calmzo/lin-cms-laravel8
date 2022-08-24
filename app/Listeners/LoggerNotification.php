<?php

namespace App\Listeners;

use App\Events\Logger;
use App\Models\Admin\LinLog;
use App\Services\Token\LoginTokenService;
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
            $user = LoginTokenService::user();
            $uid = $user['id'] ?? 0;
            $username = $user['username'] ?? '';
            $message = $params;
        }
        $request = request();

        $data = [
            'message' => $username . $message,
            'user_id' => $uid,
            'user_name' => $username,
            'status_code' => $request->server('REDIRECT_STATUS'),
            'method' => $request->method(),
            'path' => '/' . $request->path(),
            'authority' => null
        ];

        LinLog::query()->create($data);

    }
}
