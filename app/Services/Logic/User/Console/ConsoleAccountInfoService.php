<?php

namespace App\Services\Logic\User\Console;

use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleAccountInfoService extends LogicService
{

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);
        return $this->handleAccount($user);
    }

    protected function handleAccount(User $user)
    {
        $accountRepo = new AccountRepository();

        $account = $accountRepo->findById($user->id);

        return [
            'id' => $account->id,
            'phone' => $account->phone,
            'email' => $account->email,
        ];
    }

}
