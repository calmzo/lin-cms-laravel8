<?php

namespace App\Services\Logic\Course;

use App\Repositories\CourseRepository;
use App\Services\CategoryService;
use App\Services\Logic\LogicService;
use App\Validators\CourseQueryValidator;

class CourseListService extends LogicService
{

    public function handle($pagerQuery)
    {
        $params = $this->checkQueryParams($pagerQuery);

        /**
         * tc => top_category
         * sc => sub_category
         */
        if (!empty($params['sc'])) {

            $params['category_id'] = $params['sc'];

        } elseif (!empty($params['tc'])) {

            $categoryService = new CategoryService();

            $childCategoryIds = $categoryService->getChildCategoryIds($params['tc']);

            /**
             * 构造空记录条件
             */
            $params['category_id'] = $childCategoryIds ?: -999;
        }
        $params['published'] = 1;

        $page = $pagerQuery['page'] ?? 1;
        $sort = $pagerQuery['sort'] ?? 'latest';
        $limit = $pagerQuery['limit'] ?? 15;

        $courseRepo = new CourseRepository();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $courses = collect($paginate->items())->toArray();

        $items = [];

//        $baseUrl = kg_cos_url();

        foreach ($courses as $course) {

            if ($course['fake_user_count'] > $course['user_count']) {
                $course['user_count'] = $course['fake_user_count'];
            }

//            $course['cover'] = $baseUrl . $course['cover'];

            $items[] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'model' => $course['model'],
                'level' => $course['level'],
                'rating' => round($course['rating'], 1),
                'market_price' => (float)$course['market_price'],
                'vip_price' => (float)$course['vip_price'],
                'user_count' => $course['user_count'],
                'lesson_count' => $course['lesson_count'],
                'review_count' => $course['review_count'],
                'favorite_count' => $course['favorite_count'],
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

    protected function checkQueryParams($params)
    {
        $validator = new CourseQueryValidator();

        $query = [];

        if (isset($params['tc'])) {
            $query['tc'] = $validator->checkTopCategory($params['tc']);
        }

        if (isset($params['sc'])) {
            $query['sc'] = $validator->checkSubCategory($params['sc']);
        }

        if (isset($params['model'])) {
            $query['model'] = $validator->checkModel($params['model']);
        }

        if (isset($params['level'])) {
            $query['level'] = $validator->checkLevel($params['level']);
        }

        if (isset($params['sort'])) {
            $query['sort'] = $validator->checkSort($params['sort']);
        }

        return $query;
    }

}
