<?php

namespace App\Services\Logic\User\Console;

use App\Repositories\QuestionRepository;
use App\Services\Logic\Question\QuestionListService;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleQuestionListService extends LogicService
{

    public function handle($params)
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $questionRepo = new QuestionRepository();

        $pager = $questionRepo->paginate($params, $sort, $page, $limit);

        return $this->handleQuestions($pager);
    }

    protected function handleQuestions($pager)
    {
        $service = new QuestionListService();

        return $service->handleQuestions($pager);
    }

}
