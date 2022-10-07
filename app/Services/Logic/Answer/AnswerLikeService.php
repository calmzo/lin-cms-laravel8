<?php

namespace App\Services\Logic\Answer;

use App\Events\AnswerAfterLikeEvent;
use App\Events\AnswerAfterUndoLikeEvent;
use App\Events\UserDailyCounterIncrAnswerLikeCountEvent;
use App\Models\Answer;
use App\Models\AnswerLike;
use App\Models\User;
use App\Repositories\AnswerLikeRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\AnswerTrait;
use App\Lib\Notice\System\AnswerLikedNotice;
use App\Services\Logic\Point\History\AnswerLikedPointHistory;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Validators\UserLimitValidator;

class AnswerLikeService extends LogicService
{

    use AnswerTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $validator = new UserLimitValidator();

        $validator->checkDailyAnswerLikeLimit($user);

        $likeRepo = new AnswerLikeRepository();

        $answerLike = $likeRepo->findAnswerLike($answer->id, $user->id);

        $isFirstTime = true;

        if (!$answerLike) {

            $answerLike = AnswerLike::query()->create(['answer_id' => $answer->id, 'user_id' => $user->id]);
        } else {

            $isFirstTime = false;
            if ($answerLike->trashed()) {
                $answerLike->restore();
            } else {
                $answerLike->delete();
            }
        }

        $this->incrUserDailyAnswerLikeCount($user);

        if ($answerLike->deleted == 0) {

            $action = 'do';

            $this->incrAnswerLikeCount($answer);

            AnswerAfterLikeEvent::dispatch($answer);

        } else {

            $action = 'undo';

            $this->decrAnswerLikeCount($answer);
            AnswerAfterUndoLikeEvent::dispatch($answer);
        }

        $isOwner = $user->id == $answer->user_id;

        /**
         * 仅首次点赞发送通知和奖励积分
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleAnswerLikedNotice($answer, $user);
            $this->handleAnswerLikedPoint($answerLike);
        }

        return [
            'action' => $action,
            'count' => $answer->like_count,
        ];
    }

    protected function incrAnswerLikeCount(Answer $answer)
    {
        $answer->like_count += 1;

        $answer->save();
    }

    protected function decrAnswerLikeCount(Answer $answer)
    {
        if ($answer->like_count > 0) {
            $answer->like_count -= 1;
            $answer->save();
        }
    }

    protected function incrUserDailyAnswerLikeCount(User $user)
    {
        UserDailyCounterIncrAnswerLikeCountEvent::dispatch($user);
    }

    protected function handleAnswerLikedNotice(Answer $answer, User $sender)
    {
        $notice = new AnswerLikedNotice();

        $notice->handle($answer, $sender);
    }

    protected function handleAnswerLikedPoint(AnswerLike $answerLike)
    {
        $service = new AnswerLikedPointHistory();

        $service->handle($answerLike);
    }

}
