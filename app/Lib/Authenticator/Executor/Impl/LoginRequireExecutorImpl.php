<?php

namespace App\Lib\Authenticator\Executor\Impl;

use App\Lib\Authenticator\Executor\IExecutor;

class LoginRequireExecutorImpl implements IExecutor
{

    public function handle(array $userInfo = null, string $permissionName = ''): bool
    {
        LoginToken::getInstance()->verify();
        return true;
    }
}
