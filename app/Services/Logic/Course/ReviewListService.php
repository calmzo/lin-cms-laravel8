<?php

namespace App\Services\Logic\Course;

use App\Builders\ReviewListBuilder;
use App\Enums\ReviewEnums;
use App\Repositories\ReviewLikeRepository;
use App\Repositories\ReviewRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;

class ReviewListService extends LogicService
{

    use CourseTrait;

    public function handle($id, $params)
    {
        $course = $this->checkCourse($id);

        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;


        $params = [
            'course_id' => $course->id,
            'published' => ReviewEnums::PUBLISH_APPROVED,
        ];

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

        $users = $builder->getUsers($reviews);

        $meMappings = $this->getMeMappings($reviews);

        $items = [];

        foreach ($reviews as $review) {

            $owner = $users[$review['user_id']] ?? new \stdClass();

            $me = $meMappings[$review['id']];

            $items[] = [
                'id' => $review['id'],
                'rating' => $review['rating'],
                'content' => $review['content'],
                'like_count' => $review['like_count'],
                'create_time' => $review['create_time'],
                'update_time' => $review['update_time'],
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

    protected function getMeMappings($reviews)
    {
        $uid = AccountLoginTokenService::userId();

        $likeRepo = new ReviewLikeRepository();

        $likedIds = [];

        if ($uid > 0) {
            $likes = $likeRepo->findByUserId($uid);
            $likedIds = $likes->pluck('review_id')->toArray();
        }

        $result = [];

        foreach ($reviews as $consult) {
            $result[$consult['id']] = [
                'liked' => in_array($consult['id'], $likedIds) ? 1 : 0,
                'owned' => $consult['user_id'] == $uid ? 1 : 0,
            ];
        }

        return $result;
    }

}
