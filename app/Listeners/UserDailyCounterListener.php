<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Caches\UserDailyCounterCache;

class UserDailyCounterListener
{
    protected $counter;

    public function __construct()
    {
        $this->counter = new UserDailyCounterCache();
    }

    /**
     * 用户订单数量
     */
    public function incrOrderCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'order_count');
    }

    /**
     * 处理review事件
     */
    public function incrReviewCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'review_count');
    }


    /**
     * 处理article事件
     */
    public function incrArticleCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'article_count');
    }


    /**
     * 喜欢
     * @param $event
     */
    public function incrArticleLikeCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'article_like_count');
    }

    public function incrReportCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'report_count');
    }


    public function incrReviewLikeCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'review_like_count');
    }

    public function incrQuestionLikeCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'question_like_count');
    }

    public function incrAnswerCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'answer_count');
    }

    public function incrAnswerLikeCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'answer_like_count');
    }

    public function incrChapterLikeCount($event)
    {
        $this->counter->hIncrBy($event->user->id, 'chapter_like_count');
    }


    /**
     * 为事件订阅者注册监听器
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserDailyCounterIncrOrderCountEvent',
            [UserDailyCounterListener::class, 'incrOrderCount']
        );

        $events->listen(
            'App\Events\IncrReviewCountEvent',
            [UserDailyCounterListener::class, 'incrReviewCount']
        );

        $events->listen(
            'App\Events\UserDailyCounterIncrArticleCountEvent',
            [UserDailyCounterListener::class, 'incrArticleCount']
        );

        $events->listen(
            'App\Events\UserDailyCounterIncrArticleLikeCountEvent',
            [UserDailyCounterListener::class, 'incrArticleLikeCount']
        );

        $events->listen(
            'App\Events\UserDailyCounterIncrReportCountEvent',
            [UserDailyCounterListener::class, 'incrReportCount']
        );

        $events->listen(
            'App\Events\UserDailyCounterIncrReviewLikeCountEvent',
            [UserDailyCounterListener::class, 'incrReviewLikeCount']
        );

        $events->listen(
            'App\Events\UserDailyCounterIncrQuestionLikeCountEvent',
            [UserDailyCounterListener::class, 'incrQuestionLikeCount']
        );

        $events->listen(
            'App\Events\UserDailyCounterIncrAnswerCountEvent',
            [UserDailyCounterListener::class, 'incrAnswerCount']
        );
        $events->listen(
            'App\Events\UserDailyCounterIncrAnswerLikeCountEvent',
            [UserDailyCounterListener::class, 'incrAnswerLikeCount']
        );
        $events->listen(
            'App\Events\UserDailyCounterIncrChapterLikeCountEvent',
            [UserDailyCounterListener::class, 'incrChapterLikeCount']
        );
    }
}
