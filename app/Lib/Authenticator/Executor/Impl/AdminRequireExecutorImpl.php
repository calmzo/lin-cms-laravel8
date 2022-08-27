<?php

namespace App\Lib\Authenticator\Executor\Impl;

use App\Lib\Authenticator\Executor\IExecutor;

class AdminRequireExecutorImpl implements IExecutor
{

    public function handle(array $userInfo = null, string $permissionName = ''): bool
    {
        return $userInfo['admin'];
    }
}
