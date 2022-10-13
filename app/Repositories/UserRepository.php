<?php

namespace App\Repositories;

use App\Enums\AnswerEnums;
use App\Enums\ArticleEnums;
use App\Enums\QuestionEnums;
use App\Models\Answer;
use App\Models\Article;
use App\Models\Notification;
use App\Models\Question;
use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = User::query();

        if (!empty($where['id'])) {
            $query->where('id', $where['id']);
        }

        if (!empty($where['name'])) {
            $query->where('name', 'like', '%'.$where['name'].'%');
        }

        if (!empty($where['edu_role'])) {
            if (is_array($where['edu_role'])) {
                $query->whereIn('edu_role', $where['edu_role']);
            } else {
                $query->where('edu_role', $where['edu_role']);
            }
        }

        if (!empty($where['admin_role'])) {
            if (is_array($where['admin_role'])) {
                $query->whereIn('admin_role', $where['admin_role']);
            } else {
                $query->where('admin_role', $where['admin_role']);
            }
        }

        if (isset($where['vip'])) {
            $query->where('vip', $where['vip']);
        }

        if (isset($where['locked'])) {
            $query->where('locked', $where['locked']);
        }

        switch ($sort) {
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query->paginate($count, ['*'], 'page', $page);

    }


    public function countUsers()
    {
        return User::query()->count();
    }

    public function countVipUsers()
    {
        return User::query()->where('vip', 1)->count();
    }

    public function findById($id)
    {
        return User::query()->find($id);
    }

    public function findByName($name)
    {
        return User::query()->where('name', $name)->first();
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

    public function findByIds($ids, $columns = '*')
    {
        return User::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }

    public function findShallowUserByIds($ids)
    {
        return User::query()
            ->whereIn('id', $ids)
            ->get(['id', 'name', 'avatar', 'vip', 'title', 'about']);
    }

    public function countUnreadNotifications($userId)
    {
        return Notification::query()->where('receiver_id', $userId)->where('viewed', 0)->count();
    }

    public function countQuestions($userId)
    {
        return Question::query()->where('user_id', $userId)->where('published', QuestionEnums::PUBLISH_APPROVED)->count();
    }

    public function countAnswers($userId)
    {
        return Answer::query()->where('user_id', $userId)->where('published', AnswerEnums::PUBLISH_APPROVED)->count();

    }
}
