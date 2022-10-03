<?php

namespace App\Services;

use App\Services\Logic\User\Console\ConsoleAccountInfoService;
use App\Services\Logic\User\Console\ConsoleAnswerListService;
use App\Services\Logic\User\Console\ConsoleArticleListService;
use App\Services\Logic\User\Console\ConsoleConsultList;
use App\Services\Logic\User\Console\ConsoleConsultListService;
use App\Services\Logic\User\Console\ConsoleFavoriteListService;
use App\Services\Logic\User\Console\ConsoleNotificationListService;
use App\Services\Logic\User\Console\ConsoleNotificationReadService;
use App\Services\Logic\User\Console\ConsoleNotifyStatsService;
use App\Services\Logic\User\Console\ConsoleOnlineService;
use App\Services\Logic\User\Console\ConsoleOrderListService;
use App\Services\Logic\User\Console\ConsoleProfileInfoService;
use App\Services\Logic\User\Console\ConsoleProfileUpdate;
use App\Services\Logic\User\Console\ConsoleQuestionListService;
use App\Services\Logic\User\Console\ConsoleRefundListService;
use App\Services\Logic\User\Console\ConsoleReviewListService;

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

    public function getConsults($params)
    {
        $service = new ConsoleConsultListService();
        return  $service->handle($params);
    }

    public function getReviews($params)
    {
        $service = new ConsoleReviewListService();
        return  $service->handle($params);
    }

    public function getOrders($params)
    {
        $service = new ConsoleOrderListService();
        return  $service->handle($params);
    }

    public function getRefunds($params)
    {
        $service = new ConsoleRefundListService();
        return  $service->handle($params);
    }

    public function getNotifications($params)
    {
        $service = new ConsoleNotificationListService();
        $pager = $service->handle($params);
        $service = new ConsoleNotificationReadService();
        $service->handle();
        return  $pager;
    }

    public function getNotifyStats($params)
    {
        $service = new ConsoleNotifyStatsService();
        return  $service->handle($params);
    }

    public function updateProfile($params)
    {
        $service = new ConsoleProfileUpdate();
        return  $service->handle($params);
    }

    public function online()
    {
        $service = new ConsoleOnlineService();
        return  $service->handle();
    }

}
