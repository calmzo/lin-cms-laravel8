<?php

namespace App\Validators;

use App\Caches\UserDailyCounterCache;
use App\Exceptions\BadRequestException;
use App\Models\User;
use App\Utils\CodeResponse;

class UserLimitValidator extends BaseValidator
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new UserDailyCounterCache();
    }

    public function checkFavoriteLimit(User $user)
    {
        $limit = $user->vip ? 1000 : 500;

        if ($user->favorite_count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_favorite_limit');
        }
    }

    public function checkDailyReportLimit(User $user)
    {
        $count = $this->counter->hGet($user->id, 'report_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_report_limit');
        }
    }

    public function checkDailyArticleLimit(User $user)
    {
        $count = $this->counter->hGet($user->id, 'article_count');

        $limit = $user->vip ? 10 : 5;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_article_limit');
        }
    }

    public function checkDailyQuestionLimit(User $user)
    {
        $count = $this->counter->hGet($user->id, 'question_count');

        $limit = $user->vip ? 10 : 5;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_question_limit');
        }
    }

    public function checkDailyAnswerLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'answer_count');

        $limit = $user->vip ? 20 : 10;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_answer_limit');
        }
    }

    public function checkDailyCommentLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'comment_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_comment_limit');
        }
    }

    public function checkDailyDanmuLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'danmu_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_danmu_limit');
        }
    }

    public function checkDailyConsultLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_count');

        $limit = $user->vip ? 20 : 10;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_consult_limit');
        }
    }

    public function checkDailyOrderLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'order_count');

        if ($count > 50) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_order_limit');
        }
    }

    public function checkDailyArticleLikeLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'article_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyQuestionLikeLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'question_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyAnswerLikeLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'answer_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyChapterLikeLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'chapter_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyConsultLikeLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyReviewLikeLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'review_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyCommentLikeLimit(user $user)
    {
        $count = $this->counter->hGet($user->id, 'comment_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_like_limit');
        }
    }

}
