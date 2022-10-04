<?php

namespace App\Services\Logic\User\Console;

use App\Caches\UserCache;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Validators\UserValidator;
use App\Services\Token\AccountLoginTokenService;

class ConsoleProfileUpdate extends LogicService
{

    public function handle($params)
    {

        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);
        $validator = new UserValidator();

        $data = [];
        if (!empty($params['name'])) {
            $data['name'] = $params['name'];
            if ($data['name'] != $user->name) {
                $validator->checkIfNameTaken($data['name']);
            }
        }

        if (!empty($params['gender'])) {
            $data['gender'] = $validator->checkGender($params['gender']);
        }

        if (!empty($params['area'])) {
            $data['area'] = $validator->checkArea($params['area']);
        }

        if (!empty($params['about'])) {
            $data['about'] = $params['about'];
        }

        if (!empty($params['avatar'])) {
            $data['avatar'] = $params['avatar'];
        }

        $user->update($data);

        $this->rebuildUserCache($user->id);

        return $user;
    }

    protected function rebuildUserCache($id)
    {
        $cache = new UserCache();

        $cache->rebuild($id);
    }

}
