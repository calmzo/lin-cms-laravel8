<?php

namespace App\Lib\Authenticator\Executor;

interface IExecutor
{
    public function handle(array $userInfo = null, string $permissionName = ''): bool;
}
