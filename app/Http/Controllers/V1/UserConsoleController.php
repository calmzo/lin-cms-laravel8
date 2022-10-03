<?php

namespace App\Http\Controllers\V1;

use App\Services\UserConsoleService;
use App\Validates\ArticleFormValidate;
use App\Validates\V1\User\Console\ConcoleProfileFormValidate;
use Illuminate\Http\Request;

class UserConsoleController extends BaseController
{
    public $except = [];

    public function getUserConsole()
    {
        $service = new UserConsoleService();
        $pager = $service->getUserConsole();
        return $this->success($pager);
    }

    public function getUserAccount()
    {
        $service = new UserConsoleService();
        $account = $service->getUserAccount();
        return $this->success($account);
    }

    public function getUserArticles(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getUserArticles($params);
        return $this->success($pager);
    }

    public function getUserQuestions(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getUserQuestions($params);
        return $this->success($pager);
    }

    public function getAnswers(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getAnswers($params);
        return $this->success($pager);
    }

    public function getFavorites(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getFavorites($params);
        return $this->success($pager);
    }

    public function getConsults(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getConsults($params);
        return $this->success($pager);
    }

    public function getReviews(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getReviews($params);
        return $this->success($pager);
    }

    public function getOrders(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getOrders($params);
        return $this->success($pager);
    }

    public function getRefunds(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getRefunds($params);
        return $this->success($pager);
    }

    public function getNotifications(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $pager = $service->getNotifications($params);
        return $this->success($pager);
    }

    public function getNotifyStats(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $stats = $service->getNotifyStats($params);
        return $this->success($stats);
    }

    public function updateProfile(ConcoleProfileFormValidate $concoleProfileFormValidate)
    {
        $params = $concoleProfileFormValidate->check();
        $service = new UserConsoleService();
        $service->updateProfile($params);
        return $this->success();
    }

    public function online()
    {
        $service = new UserConsoleService();
        $service->online();
        return $this->success();
    }
}
