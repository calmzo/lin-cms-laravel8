<?php

namespace App\Lib\Authenticator;

use App\Lib\Authenticator\Executor\IExecutor;
use App\Lib\Authenticator\Executor\Impl\LoginRequireExecutorImpl;
use App\Lib\Authenticator\Executor\Impl\AdminRequireExecutorImpl;
use App\Lib\Authenticator\Executor\Impl\GroupRequireExecutorImpl;
use App\Enums\PermissionLevelEnums;

class AuthenticatorExecutorFactory
{
    public static function getInstance(string $level): IExecutor
    {
        $instance = null;
        switch ($level) {
            case PermissionLevelEnums::LOGIN_REQUIRED:
                $instance = new LoginRequireExecutorImpl();
                break;
            case PermissionLevelEnums::GROUP_REQUIRED:
                $instance = new GroupRequireExecutorImpl();
                break;
            case PermissionLevelEnums::ADMIN_REQUIRED:
                $instance = new AdminRequireExecutorImpl();
                break;
        }
        return $instance;
    }
}
