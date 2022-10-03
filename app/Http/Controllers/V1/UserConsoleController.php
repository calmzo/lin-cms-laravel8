<?php

namespace App\Http\Controllers\V1;

use App\Services\UserConsoleService;
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
}
