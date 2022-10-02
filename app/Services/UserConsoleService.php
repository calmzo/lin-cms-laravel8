<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\Logic\User\Console\AccountInfoService;
use App\Services\Logic\User\Console\ProfileInfoService;
use App\Services\Token\AccountLoginTokenService;

class UserConsoleService extends BaseService
{
    public function getUserConsole()
    {
        $service = new ProfileInfoService();
        return $service->handle();
    }

    public function getUserAccount()
    {
        $service = new AccountInfoService();
        return  $service->handle();
    }

}
