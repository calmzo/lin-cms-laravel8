<?php

namespace App\Services\Logic\User\Console;

use App\Repositories\NotificationRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleNotificationReadService extends LogicService
{

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();

        $notifyRepo = new NotificationRepository();

        $notifyRepo->markAllAsViewed($uid);
    }

}
