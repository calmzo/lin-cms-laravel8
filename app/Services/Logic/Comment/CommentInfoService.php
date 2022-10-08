<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment;
use App\Models\User;
use App\Repositories\AnswerLikeRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserTrait;

class CommentInfoService extends LogicService
{

    use CommentTrait;
    use UserTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = AccountLoginTokenService::userModel();

        return $this->handleComment($comment, $user);
    }

    protected function handleComment(Comment $comment, User $user)
    {
        $toUser = $this->handleShallowUserInfo($comment->to_user_id);
        $owner = $this->handleShallowUserInfo($comment->user_id);
        $me = $this->handleMeInfo($comment, $user);

        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'published' => $comment->published,
            'deleted' => $comment->deleted,
            'parent_id' => $comment->parent_id,
            'like_count' => $comment->like_count,
            'reply_count' => $comment->reply_count,
            'create_time' => $comment->create_time,
            'update_time' => $comment->update_time,
            'to_user' => $toUser,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleMeInfo(Comment $comment, User $user)
    {
        $me = [
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $comment->user_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new AnswerLikeRepository();

            $like = $likeRepo->findAnswerLike($comment->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
