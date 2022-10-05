<?php

namespace App\Services\Logic\Question;


use App\Events\QuestionAfterLikeEvent;
use App\Events\QuestionAfterUndoLikeEvent;
use App\Events\UserDailyCounterIncrQuestionLikeCountEvent;
use App\Lib\Notice\System\QuestionLiked;
use App\Models\Question;
use App\Models\User;
use App\Repositories\QuestionLikeRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Logic\Point\History\QuestionLikedPointHistory;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Validators\UserLimitValidator;
use App\Models\QuestionLike;

class QuestionLikeService extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);

        $validator = new UserLimitValidator();

        $validator->checkDailyQuestionLikeLimit($user);

        $likeRepo = new QuestionLikeRepository();

        $questionLike = $likeRepo->findQuestionLike($question->id, $user->id);

        $isFirstTime = true;

        if (!$questionLike) {

            $questionLike = QuestionLike::query()->create(['question_id' => $question->id, 'user_id' => $user->id]);

        } else {

            $isFirstTime = false;
            if ($questionLike->trashed()) {
                $questionLike->restore();
            } else {
                $questionLike->delete();
            }
        }

        $this->incrUserDailyQuestionLikeCount($user);

        if ($questionLike->deleted == 0) {

            $action = 'do';

            $this->incrQuestionLikeCount($question);
            QuestionAfterLikeEvent::dispatch($question);

        } else {

            $action = 'undo';

            $this->decrQuestionLikeCount($question);
            QuestionAfterUndoLikeEvent::dispatch($question);
        }

        $isOwner = $user->id == $question->user_id;

        /**
         * 仅首次点赞发送通知和奖励积分
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleQuestionLikedNotice($question, $user);
            $this->handleQuestionLikedPoint($questionLike);
        }

        return [
            'action' => $action,
            'count' => $question->like_count,
        ];
    }

    protected function incrQuestionLikeCount(Question $question)
    {
        $question->like_count += 1;

        $question->save();
    }

    protected function decrQuestionLikeCount(Question $question)
    {
        if ($question->like_count > 0) {
            $question->like_count -= 1;
            $question->save();
        }
    }

    protected function incrUserDailyQuestionLikeCount(User $user)
    {
        UserDailyCounterIncrQuestionLikeCountEvent::dispatch($user);
    }

    protected function handleQuestionLikedNotice(Question $question, User $sender)
    {
        $notice = new QuestionLiked();

        $notice->handle($question, $sender);
    }

    protected function handleQuestionLikedPoint(QuestionLike $questionLike)
    {
        $service = new QuestionLikedPointHistory();

        $service->handle($questionLike);
    }

}
