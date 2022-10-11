<?php

namespace App\Services\Logic\Comment;

use App\Enums\CommentEnums;
use App\Events\CommentAfterReplyEvent;
use App\Models\Comment;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Validators\CommentValidator;
use App\Validators\UserLimitValidator;

class CommentReplyService extends LogicService
{

    use AfterCreateTrait;
    use CommentDataTrait;
    use CommentTrait;
    use CountTrait;

    public function handle($id, $post)
    {
        $user = AccountLoginTokenService::userModel();
        $comment = $this->checkComment($id);

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $parent = $comment;

        $validator = new CommentValidator();

        $data = $this->handlePostData($post);
        $data['parent_id'] = $parent->id;
        $data['item_id'] = $comment->item_id;
        $data['item_type'] = $comment->item_type;
        $data['user_id'] = $user->id;
        $data['published'] = $this->getPublishStatus($user);

        $item = $validator->checkItem($comment->item_id, $comment->item_type);

        /**
         * 子评论中回复用户
         */
        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $data['parent_id'] = $parent->id;
            $data['to_user_id'] = $comment->user_id;
        }

        $reply = Comment::query()->create($data);

        $this->incrUserDailyCommentCount($user);

        if ($reply->published == CommentEnums::PUBLISH_APPROVED) {
            $this->incrCommentReplyCount($parent);
            $this->incrItemCommentCount($item);
            $this->incrUserCommentCount($user);
            $this->handleCommentRepliedNotice($reply);
            $this->handleCommentPostPoint($reply);
        }

        CommentAfterReplyEvent::dispatch($reply);
        return $reply;
    }

}
