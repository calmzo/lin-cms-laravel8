<?php

namespace App\Services\Logic\Answer;

use App\Enums\CommentEnums;
use App\Repositories\CommentRepository;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Comment\ListTrait;
use App\Services\Logic\LogicService;

class CommentListService extends LogicService
{

    use AnswerTrait;
    use ListTrait;

    public function handle($id, $params)
    {
        $answer = $this->checkAnswer($id);

        $params['item_id'] = $answer->id;
        $params['item_type'] = CommentEnums::ITEM_ANSWER;
        $params['published'] = CommentEnums::PUBLISH_APPROVED;
        $params['parent_id'] = 0;

        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $commentRepo = new CommentRepository();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
