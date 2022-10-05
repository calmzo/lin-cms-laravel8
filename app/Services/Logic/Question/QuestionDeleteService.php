<?php

namespace App\Services\Logic\Question;


use App\Events\QuestionAfterDeleteEvent;
use App\Models\Question;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Sync\QuestionIndexSync;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Validators\QuestionValidator;

class QuestionDeleteService extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);

        $validator = new QuestionValidator();

        $validator->checkOwner($user->id, $question->user_id);

        $validator->checkIfAllowDelete($question);

        $question->delete();

        $this->recountUserQuestions($user);

        $this->rebuildQuestionIndex($question);
        QuestionAfterDeleteEvent::dispatch($question);
        return $question;
    }

    protected function recountUserQuestions(User $user)
    {
        $userRepo = new UserRepository();

        $questionCount = $userRepo->countQuestions($user->id);

        $user->question_count = $questionCount;

        $user->save();
    }

    protected function rebuildQuestionIndex(Question $question)
    {
        $sync = new QuestionIndexSync();

        $sync->addItem($question->id);
    }

}
