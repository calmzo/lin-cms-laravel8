<?php

namespace App\Services\Logic\Comment;

use App\Builders\CommentListBuilder;
use App\Repositories\CommentLikeRepository;
use App\Services\Token\AccountLoginTokenService;

trait ListTrait
{

    public function handleComments($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $comments = collect($paginate->items())->toArray();

        $builder = new CommentListBuilder();

        $users = $builder->getUsers($comments);

        $meMappings = $this->getMeMappings($comments);

        $items = [];

        foreach ($comments as $comment) {

            $owner = $users[$comment['user_id']] ?? new \stdClass();
            $toUser = $users[$comment['to_user_id']] ?? new \stdClass();
            $me = $meMappings[$comment['id']];

            $items[] = [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'parent_id' => $comment['parent_id'],
                'like_count' => $comment['like_count'],
                'reply_count' => $comment['reply_count'],
                'create_time' => $comment['create_time'],
                'update_time' => $comment['update_time'],
                'to_user' => $toUser,
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);
        return $paginate;
    }

    protected function getMeMappings($comments)
    {
        $uid = AccountLoginTokenService::userId();

        $likeRepo = new CommentLikeRepository();

        $likedIds = [];

        if ($uid > 0) {
            $likes = $likeRepo->findByUserId($uid);
            $likedIds = $likes->pluck('comment_id')->toArray();
        }

        $result = [];

        foreach ($comments as $comment) {
            $result[$comment['id']] = [
                'liked' => in_array($comment['id'], $likedIds) ? 1 : 0,
                'owned' => $comment['user_id'] == $uid ? 1 : 0,
            ];
        }

        return $result;
    }

}
