<?php

namespace App\Services\Logic\Vip;

use App\Repositories\CourseRepository;
use App\Services\Logic\LogicService;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseListService extends LogicService
{

    public function handle($type)
    {
        $params = $this->request->all();
        $params['published'] = 1;
        $sort = $type == 'discount' ? 'vip_discount' : 'vip_free';
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 15;
        $courseRepo = new CourseRepository();
        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    /**
     * @param $paginate LengthAwarePaginator
     * @return object
     */
    protected function handleCourses($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $courses = collect($paginate->items())->toArray();

//        $baseUrl = kg_cos_url();
        $items = [];

        foreach ($courses as $course) {
//            $course['cover'] = $baseUrl . $course['cover'];

            $items[] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'market_price' => (float)$course['market_price'],
                'vip_price' => (float)$course['vip_price'],
                'rating' => (float)$course['rating'],
                'model' => $course['model'],
                'level' => $course['level'],
                'user_count' => $course['user_count'],
                'lesson_count' => $course['lesson_count'],
            ];
        }
        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
