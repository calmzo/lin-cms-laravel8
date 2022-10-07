<?php

namespace App\Services\Logic\Answer;

use App\Events\AnswerAfterUpdateEvent;
use App\Repositories\UserRepository;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Validators\AnswerValidator;

class AnswerUpdateService extends LogicService
{
    use AnswerDataTrait;
    use QuestionTrait;
    use AnswerTrait;

    public function handle($id, $params)
    {
        $answer = $this->checkAnswer($id);

        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $validator = new AnswerValidator();

        $validator->checkOwner($user->id, $answer->user_id);

        $validator->checkIfAllowEdit($answer);

        $data = $this->handlePostData($params);

        $answer->update($data);

        $this->saveDynamicAttrs($answer);
        AnswerAfterUpdateEvent::dispatch($answer);

        return $answer;
    }

}
