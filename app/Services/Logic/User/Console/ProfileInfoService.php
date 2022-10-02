<?php

namespace App\Services\Logic\User\Console;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ProfileInfoService extends LogicService
{
    public function handle()
    {
        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);
        return $this->handleUser($user);
    }

    protected function handleUser(User $user)
    {
        $user->area = $this->handleArea($user->area);
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'area' => $user->area,
            'gender' => $user->gender,
            'vip' => $user->vip,
            'locked' => $user->locked,
            'edu_role' => $user->edu_role,
            'admin_role' => $user->admin_role,
            'vip_expiry_time' => $user->vip_expiry_time,
            'lock_expiry_time' => $user->lock_expiry_time,
            'create_time' => $user->create_time,
            'update_time' => $user->update_time,
        ];
    }

    protected function handleArea($area)
    {
        $area = explode('/', $area);

        return [
            'province' => $area[0] ?? '',
            'city' => $area[1] ?? '',
            'county' => $area[2] ?? '',
        ];
    }

}
