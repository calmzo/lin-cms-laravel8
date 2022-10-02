<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\Logic\User\Console\ProfileInfoService;
use App\Services\Token\AccountLoginTokenService;

class UserConsoleService extends BaseService
{
    public function getUserConsole()
    {
        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);
        $service = new ProfileInfoService();
        $profile = $service->handleUser($user);
        return $profile;

    }

}
