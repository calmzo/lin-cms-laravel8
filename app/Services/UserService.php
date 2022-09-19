<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function countVipUsers()
    {
        return User::query()->where('vip', 1)->count();
    }

    public function countUsers()
    {
        return User::query()->count();
    }
}
