<?php

namespace App\Services\Logic\Answer;

use App\Events\AnswerAfterDeleteEvent;
use App\Models\Question;
use App\Models\User;
use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Validators\AnswerValidator;

class AnswerDeleteService extends LogicService
{

    use QuestionTrait;
    use AnswerTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $question = $this->checkQuestion($answer->question_id);

        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $validator = new AnswerValidator();

        $validator->checkOwner($user->id, $answer->user_id);

        $validator->checkIfAllowDelete($answer);
        $answer->delete();

        $this->recountQuestionAnswers($question);
        $this->recountUserAnswers($user);
        AnswerAfterDeleteEvent::dispatch($answer);

        return $answer;
    }

    protected function recountQuestionAnswers(Question $question)
    {
        $questionRepo = new QuestionRepository();

        $answerCount = $questionRepo->countAnswers($question->id);

        $question->answer_count = $answerCount;

        $question->save();
    }

    protected function recountUserAnswers(User $user)
    {
        $userRepo = new UserRepository();

        $answerCount = $userRepo->countAnswers($user->id);

        $user->answer_count = $answerCount;

        $user->save();
    }

}
