<?php

namespace App\Services\Logic\Course;

use App\Builders\ConsultListBuilder;
use App\Repositories\ConsultLikeRepository;
use App\Services\Token\AccountLoginTokenService;

trait ConsultListTrait
{

    protected function handleConsults($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $consults = collect($paginate->items())->toArray();

        $builder = new ConsultListBuilder();

        $users = $builder->getUsers($consults);

        $meMappings = $this->getMeMappings($consults);

        $items = [];

        foreach ($consults as $consult) {

            $owner = $users[$consult['user_id']] ?? new \stdClass();

            $me = $meMappings[$consult['id']];

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'like_count' => $consult['like_count'],
                'reply_time' => $consult['reply_time'],
                'create_time' => $consult['create_time'],
                'update_time' => $consult['update_time'],
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

    protected function getMeMappings($consults)
    {
        $uid = AccountLoginTokenService::userId();
        $likeRepo = new ConsultLikeRepository();

        $likedIds = [];

        if ($uid > 0) {
            $likes = $likeRepo->findByUserId($uid);
            $likedIds = $likes->pluck('consult_id')->toArray();
        }

        $result = [];

        foreach ($consults as $consult) {
            $result[$consult['id']] = [
                'liked' => in_array($consult['id'], $likedIds) ? 1 : 0,
                'owned' => $consult['user_id'] == $uid ? 1 : 0,
            ];
        }

        return $result;
    }

}
