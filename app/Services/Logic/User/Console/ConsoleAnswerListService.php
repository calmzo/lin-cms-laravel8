<?php

namespace App\Services\Logic\User\Console;

use App\Repositories\AnswerRepository;
use App\Services\Logic\Answer\AnswerListService;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleAnswerListService extends LogicService
{

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;

        $params['deleted'] = 0;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;
        $answerRepo = new AnswerRepository();

        $pager = $answerRepo->paginate($params, $sort, $page, $limit);

        return $this->handleAnswers($pager);
    }

    protected function handleAnswers($pager)
    {
        $service = new AnswerListService();

        return $service->handleAnswers($pager);
    }

}
