<?php

namespace App\Services;

use App\Models\User;
use App\Services\Logic\User\UserAnswerList;
use App\Services\Logic\User\UserQuestionList;
use App\Services\Logic\User\UserArticleListService;
use App\Services\Logic\User\UserInfoService;

class UserService
{

    public function getUser($id)
    {
        $service = new UserInfoService();

        return $service->handle($id);
    }


    public function getArticles($id, $params)
    {
        $service = new UserArticleListService();

        return $service->handle($id, $params);
    }

    public function getQuestions($id, $params)
    {
        $service = new UserQuestionList();

        return $service->handle($id, $params);
    }

    public function getAnswers($id, $params)
    {
        $service = new UserAnswerList();

        return $service->handle($id, $params);
    }

    public function findUserByIds($ids)
    {
        return User::query()
            ->whereIn('id', $ids)
            ->get(['id', 'name', 'avatar', 'vip', 'title', 'about']);
    }
}
