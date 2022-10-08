<?php

namespace App\Services\Logic\Comment;

use App\Enums\CommentEnums;
use App\Repositories\CommentRepository;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\LogicService;

class ReplyListService extends LogicService
{

    use CommentTrait;
    use ListTrait;

    public function handle($id, $params)
    {
        $comment = $this->checkComment($id);
        $params['parent_id'] = $comment->id;
        $params['published'] = CommentEnums::PUBLISH_APPROVED;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $commentRepo = new CommentRepository();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
