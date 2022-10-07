<?php

namespace App\Services\Logic\Answer;

use App\Models\Answer;
use App\Models\User;
use App\Repositories\AnswerLikeRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserTrait;

class AnswerInfoService extends LogicService
{

    use AnswerTrait;
    use UserTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);
        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        return $this->handleAnswer($answer, $user);
    }

    protected function handleAnswer(Answer $answer, User $user)
    {
        $question = $this->handleQuestionInfo($answer->question_id);
        $owner = $this->handleShallowUserInfo($answer->user_id);
        $me = $this->handleMeInfo($answer, $user);

        return [
            'id' => $answer->id,
            'content' => $answer->content,
            'anonymous' => $answer->anonymous,
            'accepted' => $answer->accepted,
            'published' => $answer->published,
            'deleted' => $answer->deleted,
            'comment_count' => $answer->comment_count,
            'like_count' => $answer->like_count,
            'create_time' => $answer->create_time,
            'update_time' => $answer->update_time,
            'question' => $question,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleQuestionInfo($questionId)
    {
        $questionRepo = new QuestionRepository();

        $question = $questionRepo->findById($questionId);

        return [
            'id' => $question->id,
            'title' => $question->title,
        ];
    }

    protected function handleMeInfo(Answer $answer, User $user)
    {
        $me = [
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $answer->user_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new AnswerLikeRepository();

            $like = $likeRepo->findAnswerLike($answer->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
