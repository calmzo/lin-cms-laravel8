<?php

namespace App\Repositories;

use App\Enums\ArticleEnums;
use App\Models\Article;
use App\Models\User;
use App\Models\UserBalance;

class UserRepository extends BaseRepository
{

    public function findById($id)
    {
        return User::query()->find($id);
    }

    public function countArticles($uid)
    {
        return Article::query()->where('user_id', $uid)->where('published', ArticleEnums::PUBLISH_APPROVED)->count();
    }

    public function findUserBalance($uid)
    {
        return UserBalance::query()->where('user_id', $uid)->first();
    }

    public function findShallowUserById($id)
    {
        return User::query()->find($id, ['id', 'name', 'avatar', 'vip', 'title', 'about']);
    }
}
