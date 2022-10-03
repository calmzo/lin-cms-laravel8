<?php


namespace App\Services\Logic\User\Console;

use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleNotifyStatsService extends LogicService
{

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();

        $noticeCount = $this->getNoticeCount($uid);

        return ['notice_count' => $noticeCount];
    }

    protected function getNoticeCount($userId)
    {
        $userRepo = new UserRepository();

        return $userRepo->countUnreadNotifications($userId);
    }

}
