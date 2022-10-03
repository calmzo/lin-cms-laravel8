<?php

namespace App\Services;

use App\Services\Logic\User\Console\ConsoleAccountInfoService;
use App\Services\Logic\User\Console\ConsoleAnswerListService;
use App\Services\Logic\User\Console\ConsoleArticleListService;
use App\Services\Logic\User\Console\ConsoleFavoriteListService;
use App\Services\Logic\User\Console\ConsoleProfileInfoService;
use App\Services\Logic\User\Console\ConsoleQuestionListService;

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

    public function getUserQuestions($params)
    {
        $service = new ConsoleQuestionListService();
        return  $service->handle($params);
    }

    public function getAnswers($params)
    {
        $service = new ConsoleAnswerListService();
        return  $service->handle($params);
    }


    public function getFavorites($params)
    {
        $service = new ConsoleFavoriteListService();
        return  $service->handle($params);
    }

}
