<?php

namespace App\Services\Logic\Question;


use App\Events\QuestionAfterFavoriteEvent;
use App\Events\QuestionAfterUndoFavoriteEvent;
use App\Lib\Notice\System\QuestionFavorited;
use App\Models\Question;
use App\Models\User;
use App\Repositories\QuestionFavoriteRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Validators\UserLimitValidator;
use App\Models\QuestionFavorite;

class QuestionFavoriteService extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);

        $validator = new UserLimitValidator();

        $validator->checkFavoriteLimit($user);

        $favoriteRepo = new QuestionFavoriteRepository();

        $favorite = $favoriteRepo->findQuestionFavorite($question->id, $user->id);

        $isFirstTime = true;

        if (!$favorite) {
            $favorite = QuestionFavorite::query()->create(['question_id' => $question->id, 'user_id', $user->id]);

        } else {

            $isFirstTime = false;

            if ($favorite->trashed()) {
                $favorite->restore();
            } else {
                $favorite->delete();
            }
        }

        if ($favorite->deleted == 0) {

            $action = 'do';

            $this->incrQuestionFavoriteCount($question);
            $this->incrUserFavoriteCount($user);
            QuestionAfterFavoriteEvent::dispatch($question);

        } else {

            $action = 'undo';

            $this->decrQuestionFavoriteCount($question);
            $this->decrUserFavoriteCount($user);
            QuestionAfterUndoFavoriteEvent::dispatch($question);
        }

        $isOwner = $user->id == $question->user_id;

        /**
         * 仅首次收藏发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleFavoriteNotice($question, $user);
        }

        return [
            'action' => $action,
            'count' => $question->favorite_count,
        ];
    }

    protected function incrQuestionFavoriteCount(Question $question)
    {
        $question->favorite_count += 1;

        $question->save();
    }

    protected function decrQuestionFavoriteCount(Question $question)
    {
        if ($question->favorite_count > 0) {
            $question->favorite_count -= 1;
            $question->save();
        }
    }

    protected function incrUserFavoriteCount(User $user)
    {
        $user->favorite_count += 1;

        $user->save();
    }

    protected function decrUserFavoriteCount(User $user)
    {
        if ($user->favorite_count > 0) {
            $user->favorite_count -= 1;
            $user->save();
        }
    }

    protected function handleFavoriteNotice(Question $question, User $sender)
    {
        $notice = new QuestionFavorited();

        $notice->handle($question, $sender);
    }

}
