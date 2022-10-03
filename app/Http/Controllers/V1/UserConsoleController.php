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
        $profile = $service->getUserConsole();
        return $this->success($profile);
    }

    public function getUserAccount()
    {
        $service = new UserConsoleService();
        $profile = $service->getUserAccount();
        return $this->success($profile);
    }

    public function getUserArticles(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getUserArticles($params);
        return $this->success($profile);
    }

    public function getUserQuestions(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getUserQuestions($params);
        return $this->success($profile);
    }

    public function getAnswers(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getAnswers($params);
        return $this->success($profile);
    }

    public function getFavorites(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getFavorites($params);
        return $this->success($profile);
    }

    public function getConsults(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getConsults($params);
        return $this->success($profile);
    }

    public function getReviews(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getReviews($params);
        return $this->success($profile);
    }

    public function getOrders(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getOrders($params);
        return $this->success($profile);
    }

    public function getRefunds(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getRefunds($params);
        return $this->success($profile);
    }

    public function getNotifications(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getNotifications($params);
        return $this->success($profile);
    }

    public function getNotifyStats(Request $request)
    {
        $params = $request->all();
        $service = new UserConsoleService();
        $profile = $service->getNotifyStats($params);
        return $this->success($profile);
    }

    public function updateProfile(ConcoleProfileFormValidate $concoleProfileFormValidate)
    {
        $params = $concoleProfileFormValidate->check();
        $service = new UserConsoleService();
        $profile = $service->updateProfile($params);
        return $this->success($profile);
    }

    public function online()
    {
        $service = new UserConsoleService();
        $profile = $service->online();
        return $this->success($profile);
    }
}
