<?php

namespace App\Builders;

use App\Repositories\UserRepository;

class CommentListBuilder
{

    public function handleUsers(array $comments)
    {
        $users = $this->getUsers($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['owner'] = $users[$comment['user_id']] ?? new \stdClass();
            $comments[$key]['to_user'] = $users[$comment['to_user_id']] ?? new \stdClass();
        }

        return $comments;
    }

    public function getUsers(array $comments)
    {
        $ownerIds = array_column_unique($comments, 'owner_id');
        $toUserIds = array_column_unique($comments, 'to_user_id');
        $ids = array_merge($ownerIds, $toUserIds);

        $userRepo = new UserRepository();

        $users = $userRepo->findShallowUserByIds($ids);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
//            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
