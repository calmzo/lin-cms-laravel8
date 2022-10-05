<?php

namespace App\Services;

use App\Enums\AnswerEnums;
use App\Enums\ArticleEnums;
use App\Enums\CommentEnums;
use App\Enums\ConsultEnums;
use App\Enums\OrderEnums;
use App\Enums\PointGiftRedeemEnums;
use App\Enums\QuestionEnums;
use App\Enums\RefundEnums;
use App\Enums\ReviewEnums;
use App\Events\IncrOrderCountEvent;
use App\Models\Answer;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Consult;
use App\Models\PointGiftRedeem;
use App\Models\Question;
use App\Models\Refund;
use App\Models\OrderStatus;
use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use App\Models\Vip;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ClientTrait;
use App\Traits\OrderTrait;

class StatService
{

    public function countDailySales($date)
    {
        $startTime = $date;
        $endTime = date('Y-m-d', strtotime($startTime) + 86400);
        return OrderStatus::query()
            ->where('status', OrderEnums::STATUS_FINISHED)
            ->whereHas('order', function ($query) use ($startTime, $endTime) {
                $query->whereBetween('create_time', [$startTime, $endTime]);
            })
            ->count();
    }


    public function countDailyRefunds($date)
    {
        $startTime = $date;
        $endTime = date('Y-m-d', strtotime($startTime) + 86400);
        return Refund::query()
            ->where('status', RefundEnums::STATUS_FINISHED)
            ->whereBetween('create_time', [$startTime, $endTime])
            ->count();
    }


    public function sumDailySales($date)
    {
        $startTime = $date;
        $endTime = date('Y-m-d', strtotime($startTime) + 86400);
        return Order::query()
            ->whereBetween('create_time', [$startTime, $endTime])
            ->whereHas('orderStatus', function ($q) {
                $q->where('status', OrderEnums::STATUS_FINISHED);
            })
            ->sum('amount');
    }

    public function sumDailyRefunds($date)
    {
        $startTime = $date;
        $endTime = date('Y-m-d', strtotime($startTime) + 86400);
        return Refund::query()
            ->where('status', RefundEnums::STATUS_FINISHED)
            ->whereBetween('create_time', [$startTime, $endTime])
            ->sum('amount');

    }

    public function countDailyRegisteredUsers($date)
    {
        $startTime = $date;
        $endTime = date('Y-m-d', strtotime($startTime) + 86400);
        return User::query()
            ->whereBetween('create_time', [$startTime, $endTime])
            ->count();

    }

    public function countDailyPointGiftRedeems($date)
    {
        $startTime = $date;
        $endTime = date('Y-m-d', strtotime($startTime) + 86400);
        return PointGiftRedeem::query()
            ->where('status', PointGiftRedeemEnums::STATUS_PENDING)
            ->whereBetween('create_time', [$startTime, $endTime])
            ->count();
    }

    public function countPendingReviews()
    {
        return Review::query()->where('published', ReviewEnums::PUBLISH_PENDING)->count();
    }

    public function countPendingConsults()
    {
        return Consult::query()->where('published', ConsultEnums::PUBLISH_PENDING)->count();
    }

    public function countPendingArticles()
    {
        return Article::query()->where('published', ArticleEnums::PUBLISH_PENDING)->count();
    }

    public function countPendingQuestions()
    {
        return Question::query()->where('published', QuestionEnums::PUBLISH_PENDING)->count();
    }

    public function countPendingAnswers()
    {
        return Answer::query()->where('published', AnswerEnums::PUBLISH_PENDING)->count();
    }

    public function countPendingComments()
    {
        return Comment::query()->where('published', CommentEnums::PUBLISH_PENDING)->count();
    }

    public function countReportedArticles()
    {
        return Article::query()->where('report_count', '>', 0)->count();
    }

    public function countReportedQuestions()
    {
        return Question::query()->where('report_count', '>', 0)->count();
    }

    public function countReportedAnswers()
    {
        return Answer::query()->where('report_count', '>', 0)->count();
    }

    public function countReportedComments()
    {
        return Comment::query()->where('report_count', '>', 0)->count();
    }

}
