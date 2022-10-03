<?php

namespace App\Services\Logic\User\Console;

use App\Repositories\ConsultRepository;
use App\Builders\ConsultListBuilder;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleConsultListService extends LogicService
{

    public function handle($params)
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $consultRepo = new ConsultRepository();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    protected function handleConsults($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new ConsultListBuilder();

        $consults = collect($paginate->items())->toArray();

        $courses = $builder->getCourses($consults);
        $chapters = $builder->getChapters($consults);

        $items = [];

        foreach ($consults as $consult) {

            $course = $courses[$consult['course_id']] ?? new \stdClass();
            $chapter = $chapters[$consult['chapter_id']] ?? new \stdClass();

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'priority' => $consult['priority'],
                'like_count' => $consult['like_count'],
                'reply_time' => $consult['reply_time'],
                'create_time' => $consult['create_time'],
                'course' => $course,
                'chapter' => $chapter,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
