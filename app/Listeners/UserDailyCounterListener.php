<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Caches\UserDailyCounter;

class UserDailyCounterListener
{
    protected $counter;

    public function __construct()
    {
        $this->counter = new UserDailyCounter();
    }

    /**
     * 用户订单数量
     */
    public function incrOrderCount($event) {
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


    /**
     * 为事件订阅者注册监听器
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\IncrOrderCountEvent',
            [UserDailyCounterListener::class, 'incrOrderCount']
        );

        $events->listen(
            'App\Events\IncrReviewCountEvent',
            [UserDailyCounterListener::class, 'incrReviewCount']
        );

        $events->listen(
            'App\Events\IncrArticleCountEvent',
            [UserDailyCounterListener::class, 'incrArticleCount']
        );

        $events->listen(
            'App\Events\IncrArticleLikeCountEvent',
            [UserDailyCounterListener::class, 'incrArticleLikeCount']
        );
    }
}
