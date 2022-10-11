<?php

namespace App\Services\Logic\Comment;

use App\Events\CommentAfterDeleteEvent;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Validators\CommentValidator;

class CommentDeleteService extends LogicService
{

    use CommentTrait;
    use CountTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = AccountLoginTokenService::userModel();

        $validator = new CommentValidator();

        $validator->checkOwner($user->id, $comment->user_id);

        $comment->delete();

        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $this->decrCommentReplyCount($parent);
        }

        $item = $validator->checkItem($comment->item_id, $comment->item_type);

        $this->decrItemCommentCount($item);
        $this->decrUserCommentCount($user);
        CommentAfterDeleteEvent::dispatch($comment);
    }

}
