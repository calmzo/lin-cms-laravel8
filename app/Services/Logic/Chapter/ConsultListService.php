<?php

namespace App\Services\Logic\Chapter;
use App\Enums\ConsultEnums;
use App\Repositories\ConsultRepository;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Course\ConsultListTrait;
use App\Services\Logic\LogicService;

class ConsultListService extends LogicService
{

    use ChapterTrait;
    use ConsultListTrait;

    public function handle($id, $params)
    {
        $chapter = $this->checkChapter($id);
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $params = [
            'chapter_id' => $chapter->id,
            'published' => ConsultEnums::PUBLISH_APPROVED,
            'private' => 0,
        ];

        $consultRepo = new ConsultRepository();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

}
