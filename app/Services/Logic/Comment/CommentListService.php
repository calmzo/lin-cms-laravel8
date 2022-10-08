<?php

namespace App\Services\Logic\Comment;

use App\Enums\CommentEnums;
use App\Repositories\CommentRepository;
use App\Services\Logic\LogicService;

class CommentListService extends LogicService
{

    use ListTrait;

    public function handle($params)
    {
        $params['parent_id'] = 0;
        $params['published'] = CommentEnums::PUBLISH_APPROVED;

        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $commentRepo = new CommentRepository();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
