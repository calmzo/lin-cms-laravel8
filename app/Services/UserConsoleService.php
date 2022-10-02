<?php

namespace App\Services;

use App\Services\Logic\User\Console\ConsoleAccountInfoService;
use App\Services\Logic\User\Console\ConsoleArticleListService;
use App\Services\Logic\User\Console\ConsoleProfileInfoService;

class UserConsoleService extends BaseService
{
    public function getUserConsole()
    {
        $service = new ConsoleProfileInfoService();
        return $service->handle();
    }

    public function getUserAccount()
    {
        $service = new ConsoleAccountInfoService();
        return  $service->handle();
    }

    public function getUserArticles($params)
    {
        $service = new ConsoleArticleListService();
        return  $service->handle($params);
    }

}
