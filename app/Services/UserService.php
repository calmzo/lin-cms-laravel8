<?php

namespace App\Services;

use App\Enums\ArticleEnums;
use App\Models\Article;
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

    public function countArticles($uid)
    {
        return Article::query()->where('user_id', $uid)->where('published', ArticleEnums::PUBLISH_APPROVED)->count();
    }
}
