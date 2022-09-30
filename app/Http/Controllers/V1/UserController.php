<?php

namespace App\Http\Controllers\V1;


use App\Services\UserService;

class UserController extends BaseController
{
    public $except = [];

    //
    public function getUser($id)
    {
        $service = new UserService();
        $user = $service->getUser($id);
        return $this->success($user);
    }

}
