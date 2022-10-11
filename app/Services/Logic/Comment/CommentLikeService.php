<?php

namespace App\Services\Logic\Comment;

use App\Events\CommentAfterLikeEvent;
use App\Events\CommentAfterUndoLikeEvent;
use App\Events\UserDailyCounterIncrCommentLikeCountEvent;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\User;
use App\Repositories\CommentLikeRepository;
use App\Services\Logic\CommentTrait;
use App\Lib\Notice\System\CommentLikedNotice;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Validators\UserLimitValidator;

class CommentLikeService extends LogicService
{

    use CommentTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = AccountLoginTokenService::userModel();

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLikeLimit($user);

        $likeRepo = new CommentLikeRepository();

        $commentLike = $likeRepo->findCommentLike($comment->id, $user->id);

        $isFirstTime = true;

        if (!$commentLike) {
            $commentLike = CommentLike::query()->create(['comment_id' => $comment->id, 'user_id' => $user->id]);
        } else {

            $isFirstTime = false;

            if ($commentLike->trashed()) {
                $commentLike->restore();
            } else {
                $commentLike->delete();
            }

        }

        $this->incrUserDailyCommentLikeCount($user);

        if ($commentLike->deleted == 0) {

            $action = 'do';

            $this->incrCommentLikeCount($comment);
            CommentAfterLikeEvent::dispatch($comment);
        } else {

            $action = 'undo';

            $this->decrCommentLikeCount($comment);
            CommentAfterUndoLikeEvent::dispatch($comment);
        }

        $isOwner = $user->id == $comment->user_id;

        /**
         * 仅首次点赞发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleCommentLikedNotice($comment, $user);
        }

        return [
            'action' => $action,
            'count' => $comment->like_count,
        ];
    }

    protected function incrCommentLikeCount(Comment $comment)
    {
        $comment->like_count += 1;

        $comment->save();
    }


    protected function decrCommentLikeCount(Comment $comment)
    {
        if ($comment->like_count > 0) {
            $comment->like_count -= 1;
            $comment->save();
        }
    }

    protected function incrUserDailyCommentLikeCount(User $user)
    {
        UserDailyCounterIncrCommentLikeCountEvent::dispatch($user);
    }

    protected function handleCommentLikedNotice(Comment $comment, User $sender)
    {
        $notice = new CommentLikedNotice();

        $notice->handle($comment, $sender);
    }

}
