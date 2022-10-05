<?php

namespace App\Traits;

use App\Models\User;
use App\Validators\UserValidator;
use App\Repositories\UserRepository;

trait UserTrait
{

    public function checkUser($id)
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

    public function checkUserCache($id)
    {
        $validator = new UserValidator();

        return $validator->checkUserCache($id);
    }

    public function handleShallowUserInfo($id)
    {
        if (empty($id)) return new \stdClass();


        $userRepo = new UserRepository();
        $user = $userRepo->findShallowUserById($id);

        if (!$user) return (object)[];

        $result = $user->toArray();

//        $result['avatar'] = kg_cos_user_avatar_url($user->avatar);

        return $result;
    }

}
