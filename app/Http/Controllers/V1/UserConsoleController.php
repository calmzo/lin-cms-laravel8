<?php

namespace App\Http\Controllers\V1;

use App\Services\UserConsoleService;

class UserConsoleController extends BaseController
{
    public $except = [];

    public function getUserConsole()
    {
        $service = new UserConsoleService();
        $profile = $service->getUserConsole();
        return $this->success($profile);
    }
}
