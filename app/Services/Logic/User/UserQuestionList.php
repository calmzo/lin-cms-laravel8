<?php

namespace App\Services\Logic\User;

use App\Enums\QuestionEnums;
use App\Repositories\QuestionRepository;
use App\Services\Logic\Question\QuestionListService;
use App\Services\Logic\LogicService;
use App\Traits\UserTrait;

class UserQuestionList extends LogicService
{

    use UserTrait;

    public function handle($id, $params)
    {
        $user = $this->checkUser($id);

        $params['user_id'] = $user->id;
        $params['published'] = QuestionEnums::PUBLISH_APPROVED;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $articleRepo = new QuestionRepository();

        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleQuestions($pager);
    }

    protected function handleQuestions($pager)
    {
        $service = new QuestionListService();

        return $service->handleQuestions($pager);
    }

}
