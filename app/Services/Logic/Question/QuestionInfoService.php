<?php

namespace App\Services\Logic\Question;

use App\Caches\CategoryCache;
use App\Enums\QuestionEnums;
use App\Events\QuestionAfterViewEvent;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use App\Repositories\QuestionFavoriteRepository;
use App\Repositories\QuestionLikeRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;
use App\Traits\UserTrait;

class QuestionInfoService extends LogicService
{

    use QuestionTrait;
    use UserTrait;

    public function handle($id)
    {
        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);

        $question = $this->checkQuestion($id);

        $result = $this->handleQuestion($question, $user);

        $this->incrQuestionViewCount($question);

        QuestionAfterViewEvent::dispatch($question);

        return $result;
    }

    protected function handleQuestion(Question $question, User $user)
    {
        $lastReplier = $this->handleShallowUserInfo($question->last_replier_id);
        $category = $this->handleCategoryInfo($question->category_id);
        $owner = $this->handleShallowUserInfo($question->owner_id);
        $me = $this->handleMeInfo($question, $user);

        return [
            'id' => $question->id,
            'title' => $question->title,
            'summary' => $question->summary,
            'content' => $question->content,
            'tags' => $question->tags,
            'bounty' => $question->bounty,
            'anonymous' => $question->anonymous,
            'solved' => $question->solved,
            'closed' => $question->closed,
            'published' => $question->published,
            'deleted' => $question->deleted,
            'view_count' => $question->view_count,
            'like_count' => $question->like_count,
            'answer_count' => $question->answer_count,
            'comment_count' => $question->comment_count,
            'favorite_count' => $question->favorite_count,
            'last_reply_time' => $question->last_reply_time,
            'create_time' => $question->create_time,
            'update_time' => $question->update_time,
            'last_replier' => $lastReplier,
            'category' => $category,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleCategoryInfo($categoryId)
    {
        $cache = new CategoryCache();

        /**
         * @var Category $category
         */
        $category = $cache->get($categoryId);

        if (!$category) return new \stdClass();

        return [
            'id' => $category->id,
            'name' => $category->name,
        ];
    }

    protected function handleMeInfo(Question $question, User $user)
    {
        $me = [
            'allow_answer' => 0,
            'liked' => 0,
            'favorited' => 0,
            'answered' => 0,
            'owned' => 0,
        ];

        $approved = $question->published == QuestionEnums::PUBLISH_APPROVED;
        $closed = $question->closed == 1;
        $solved = $question->solved == 1;

        if ($user->id == $question->user_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new QuestionLikeRepository();

            $like = $likeRepo->findQuestionLike($question->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            $favoriteRepo = new QuestionFavoriteRepository();

            $favorite = $favoriteRepo->findQuestionFavorite($question->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            $questionRepo = new QuestionRepository();

            $userAnswers = $questionRepo->findUserAnswers($question->id, $user->id);

            if ($userAnswers->count() > 0) {
                $me['answered'] = 1;
            }

            $answered = $me['answered'] == 1;

            if ($approved && !$closed && !$solved && !$answered) {
                $me['allow_answer'] = 1;
            }
        }

        return $me;
    }

    protected function incrQuestionViewCount(Question $question)
    {
        $question->view_count += 1;

        $question->save();
    }

}
