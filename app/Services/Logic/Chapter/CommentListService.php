<?php

namespace App\Services\Logic\Chapter;

use App\Enums\CommentEnums;
use App\Repositories\CommentRepository;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Comment\ListTrait;
use App\Services\Logic\LogicService;

class CommentListService extends LogicService
{

    use ChapterTrait;
    use ListTrait;

    public function handle($id, $params)
    {
        $chapter = $this->checkChapter($id);

        $params['item_id'] = $chapter->id;
        $params['item_type'] = CommentEnums::ITEM_CHAPTER;
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
