<?php

namespace App\Caches;

use App\Enums\QuestionEnums;
use App\Repositories\QuestionRepository;
use App\Services\Logic\Question\QuestionListService;

class IndexQuestionListCache extends Cache
{

    protected $lifetime = 15 * 60;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_question_list';
    }

    public function getContent($id = null)
    {
        $questionRepo = new QuestionRepository();

        $where = [
            'published' => QuestionEnums::PUBLISH_APPROVED
        ];

        $pager = $questionRepo->paginate($where, 'latest', 1, 10);

        $service = new QuestionListService();

        $pager = $service->handleQuestions($pager);

        return $pager->items ?: [];
    }

}
