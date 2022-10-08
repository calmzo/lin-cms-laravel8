<?php

namespace App\Services\Logic\Comment;

use App\Events\CommentAfterCreateEvent;
use App\Models\Comment;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Validators\CommentValidator;
use App\Validators\UserLimitValidator;

class CommentCreateService extends LogicService
{

    use AfterCreateTrait;
    use CommentDataTrait;
    use CountTrait;

    public function handle($post)
    {
        $user = AccountLoginTokenService::userModel();

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $validator = new CommentValidator();

        $item = $validator->checkItem($post['item_id'], $post['item_type']);
        $data = $this->handlePostData($post);

        $data['item_id'] = $post['item_id'];
        $data['item_type'] = $post['item_type'];
        $data['user_id'] = $user->id;
        $data['published'] = $this->getPublishStatus($user);
        $comment = Comment::query()->create($data);

        $this->incrUserDailyCommentCount($user);

        if ($comment->published == CommentEnums::PUBLISH_APPROVED) {
            $this->incrItemCommentCount($item);
            $this->incrUserCommentCount($user);
            $this->handleItemCommentedNotice($item, $comment);
            $this->handleCommentPostPoint($comment);
        }

        CommentAfterCreateEvent::dispatch($comment);

        return $comment;
    }

}
