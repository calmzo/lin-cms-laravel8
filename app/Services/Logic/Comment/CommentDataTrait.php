<?php

namespace App\Services\Logic\Comment;

use App\Enums\CommentEnums;
use App\Models\User;
use App\Traits\ClientTrait;
use App\Validators\CommentValidator;

trait CommentDataTrait
{

    use ClientTrait;

    protected function handlePostData($post)
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new CommentValidator();

        $data['content'] = $validator->checkContent($post['content']);

        return $data;
    }

    protected function getPublishStatus(User $user)
    {
        $case1 = $user->article_count > 2;
        $case2 = $user->question_count > 2;
        $case3 = $user->answer_count > 2;
        $case4 = $user->comment_count > 2;

        $status = CommentEnums::PUBLISH_PENDING;

        if ($case1 || $case2 || $case3 || $case4) {
            $status = CommentEnums::PUBLISH_APPROVED;
        }

        return $status;
    }

}
