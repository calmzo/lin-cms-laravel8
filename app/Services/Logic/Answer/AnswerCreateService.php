<?php

namespace App\Services\Logic\Answer;

use App\Enums\AnswerEnums;
use App\Events\AnswerAfterCreateEvent;
use App\Events\UserDailyCounterIncrAnswerCountEvent;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\AnswerTrait;
use App\Lib\Notice\System\QuestionAnsweredNotice;
use App\Services\Logic\Point\History\AnswerPostPointHistory;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Validators\AnswerValidator;
use App\Validators\UserLimitValidator;

class AnswerCreateService extends LogicService
{
    use AnswerDataTrait;
    use QuestionTrait;
    use AnswerTrait;

    public function handle($params)
    {
        $question = $this->checkQuestion($params['question_id']);
        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $validator = new UserLimitValidator();

        $validator->checkDailyAnswerLimit($user);

        $validator = new AnswerValidator();
        $validator->checkIfAllowAnswer($question, $user);

        $data = $this->handlePostData($params);

        $data['published'] = $this->getPublishStatus($user);
        $data['question_id'] = $question->id;
        $data['user_id'] = $user->id;

        $answer = Answer::query()->create($data);
        if ($answer->published == AnswerEnums::PUBLISH_APPROVED) {

            $question->last_answer_id = $answer->id;
            $question->last_replier_id = $answer->user_id;
            $question->last_reply_time = strtotime($answer->create_time);

            $question->save();

            if ($answer->user_id != $question->user_id) {
                $this->handleAnswerPostPoint($answer);
                $this->handleQuestionAnsweredNotice($answer);
            }
        }

        $this->saveDynamicAttrs($answer);
        $this->incrUserDailyAnswerCount($user);
        $this->recountQuestionAnswers($question);
        $this->recountUserAnswers($user);

        AnswerAfterCreateEvent::dispatch($answer);
        return $answer;
    }

    protected function incrUserDailyAnswerCount(User $user)
    {
        UserDailyCounterIncrAnswerCountEvent::dispatch($user);
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

    protected function handleQuestionAnsweredNotice(Answer $answer)
    {
        $notice = new QuestionAnsweredNotice();

        $notice->handle($answer);
    }

    protected function handleAnswerPostPoint(Answer $answer)
    {
        $service = new AnswerPostPointHistory();

        $service->handle($answer);
    }

}
