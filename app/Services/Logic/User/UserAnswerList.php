<?php

namespace App\Services\Logic\User;

use App\Enums\AnswerEnums;
use App\Repositories\AnswerRepository;
use App\Services\Logic\Answer\AnswerListService;
use App\Services\Logic\LogicService;
use App\Traits\UserTrait;

class UserAnswerList extends LogicService
{

    use UserTrait;

    public function handle($id, $params)
    {
        $user = $this->checkUser($id);
        $params['user_id'] = $user->id;
        $params['published'] = AnswerEnums::PUBLISH_APPROVED;

        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $answerRepo = new AnswerRepository();

        $paginate = $answerRepo->paginate($params, $sort, $page, $limit);

        return $this->handleAnswers($paginate);
    }

    protected function handleAnswers($paginate)
    {
        $service = new AnswerListService();

        return $service->handleAnswers($paginate);
    }

}
