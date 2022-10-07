<?php

namespace App\Services\Logic\Answer;

use App\Enums\AnswerEnums;
use App\Models\Answer;
use App\Models\User;
use App\Traits\ClientTrait;
use App\Validators\AnswerValidator;

trait AnswerDataTrait
{

    use ClientTrait;

    protected function handlePostData($post)
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new AnswerValidator();
        $data['content'] = $validator->checkContent($post['content']);

        return $data;
    }

    protected function getPublishStatus(User $user)
    {
        return $user->answer_count > 2 ? AnswerEnums::PUBLISH_APPROVED : AnswerEnums::PUBLISH_PENDING;
    }

    protected function saveDynamicAttrs(Answer $answer)
    {
        $answer->cover = kg_parse_first_content_image($answer->content);

        $answer->summary = kg_parse_summary($answer->content);

        $answer->save();
    }

}
