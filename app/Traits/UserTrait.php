<?php

namespace App\Traits;

use App\Models\User;
use App\Validators\User as UserValidator;

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


        $user = User::query()->find($id, ['id', 'name', 'avatar', 'vip', 'title', 'about']);

        if (!$user) return new \stdClass();

        $result = $user->toArray();

        $result['avatar'] = kg_cos_user_avatar_url($user->avatar);

        return $result;
    }

}
