<?php

namespace App\Services\Logic\Live;

use App\Builders\LiveListBuilder;
use App\Repositories\ChapterLiveRepository;
use App\Services\Logic\LogicService;

class LiveListService extends LogicService
{

    public function handle($params)
    {
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;


        $params = [
            'start_time' => strtotime('today'),
            'published' => 1,
        ];

        $chapterLiveRepo = new ChapterLiveRepository();

        $pager = $chapterLiveRepo->paginate($params, $sort, $page, $limit);

        return $this->handleLives($pager);
    }

    protected function handleLives($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $builder = new LiveListBuilder();

        $lives = collect($paginate->items())->toArray();

        $courses = $builder->getCourses($lives);
        $chapters = $builder->getChapters($lives);

        $items = [];

        foreach ($lives as $live) {

            $course = $courses[$live['course_id']] ?? new \stdClass();
            $chapter = $chapters[$live['chapter_id']] ?? new \stdClass();

            $items[] = [
                'id' => $live['id'],
                'status' => $live['status'],
                'start_time' => $live['start_time'],
                'end_time' => $live['end_time'],
                'course' => $course,
                'chapter' => $chapter,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
