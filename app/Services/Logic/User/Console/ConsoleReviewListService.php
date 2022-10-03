<?php

namespace App\Services\Logic\User\Console;

use App\Builders\ReviewListBuilder;
use App\Repositories\ReviewRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleReviewListService extends LogicService
{

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $reviewRepo = new ReviewRepository();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    protected function handleReviews($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $builder = new ReviewListBuilder();

        $reviews = collect($paginate->items())->toArray();

        $courses = $builder->getCourses($reviews);

        $items = [];

        foreach ($reviews as $review) {

            $course = $courses[$review['course_id']] ?? new \stdClass();

            $items[] = [
                'id' => $review['id'],
                'content' => $review['content'],
                'reply' => $review['reply'],
                'rating' => $review['rating'],
                'rating1' => $review['rating1'],
                'rating2' => $review['rating2'],
                'rating3' => $review['rating3'],
                'like_count' => $review['like_count'],
                'create_time' => $review['create_time'],
                'update_time' => $review['update_time'],
                'course' => $course,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
