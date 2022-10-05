<?php

namespace App\Services\Logic\Question;

use App\Enums\CommentEnums;
use App\Repositories\CommentRepository;
use App\Services\Logic\Comment\ListTrait;
use App\Services\Logic\LogicService;
use App\Traits\QuestionTrait;

class CommentListService extends LogicService
{

    use QuestionTrait;
    use ListTrait;

    public function handle($id, $params)
    {
        $question = $this->checkQuestion($id);
        $params['item_id'] = $question->id;
        $params['item_type'] = CommentEnums::ITEM_QUESTION;
        $params['published'] = CommentEnums::PUBLISH_APPROVED;
        $params['parent_id'] = 0;

        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'accepted';
        $limit = $params['limit'] ?? 15;

        $commentRepo = new CommentRepository();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
