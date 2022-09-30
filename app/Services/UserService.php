<?php

namespace App\Services;

use App\Enums\ArticleEnums;
use App\Exceptions\NotFoundException;
use App\Models\Article;
use App\Models\User;
use App\Services\Logic\User\UserInfoService;

class UserService
{

    public function getUser($id)
    {
        $service = new UserInfoService();

        return $service->handle($id);
    }


    public function countVipUsers()
    {
        return User::query()->where('vip', 1)->count();
    }

    public function countUsers()
    {
        return User::query()->count();
    }

    public function countArticles($uid)
    {
        return Article::query()->where('user_id', $uid)->where('published', ArticleEnums::PUBLISH_APPROVED)->count();
    }

    public function findUserByIds($ids)
    {
        return User::query()
            ->whereIn('id', $ids)
            ->get(['id', 'name', 'avatar', 'vip', 'title', 'about']);
    }
}
