<?php

namespace App\Services\Logic\Answer;

use App\Events\AnswerAfterAcceptEvent;
use App\Events\AnswerAfterUndoAcceptEvent;
use App\Models\Answer;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Logic\AnswerTrait;
use App\Lib\Notice\System\AnswerAcceptedNotice;
use App\Services\Logic\Point\History\AnswerAcceptedPointHistory;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Validators\AnswerValidator;

class AnswerAcceptService extends LogicService
{

    use AnswerTrait;
    use QuestionTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $question = $this->checkQuestion($answer->question_id);

        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $validator = new AnswerValidator();

        $validator->checkOwner($user->id, $answer->user_id);

//        if ($question->solved == 1) return $answer;

        $answer->accepted = $answer->accepted == 1 ? 0 : 1;
        $answer->save();

        if ($answer->accepted == 1) {

            $question->last_answer_id = $answer->id;
            $question->last_reply_time = time();
            $question->solved = 1;
            $question->save();

            $action = 'do';
            $this->handleAcceptNotice($answer, $user);
            AnswerAfterAcceptEvent::dispatch($answer);

        } else {

            $question->last_answer_id = 0;
            $question->last_reply_time = 0;
            $question->solved = 0;
            $question->save();

            $action = 'undo';
            AnswerAfterUndoAcceptEvent::dispatch($answer);

        }

        return [
            'action' => $action,
        ];
    }

    protected function handleAcceptPoint(Answer $answer)
    {
        $service = new AnswerAcceptedPointHistory();

        $service->handle($answer);
    }

    protected function handleAcceptNotice(Answer $answer, User $sender)
    {
        $notice = new AnswerAcceptedNotice();

        $notice->handle($answer, $sender);
    }

}
