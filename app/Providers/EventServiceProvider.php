<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Database\Events\QueryExecuted' => [
            'App\Listeners\QueryListener',
        ],
        'App\Events\Logger' => [
            'App\Listeners\LoggerNotification',
        ],
        'App\Events\TradeAfterPayEvent' => [
            'App\Listeners\TradeAfterPayListener',
        ],
        'App\Events\NoticeTaskEvent' => [
            'App\Listeners\NoticeTaskListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * 被注册的订阅者类
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\UserDailyCounterListener',
        'App\Listeners\AccountListener',
        'App\Listeners\ArticleListener',
        'App\Listeners\ReportListener',
        'App\Listeners\ReviewListener',
        'App\Listeners\QuestionListener',
    ];

}
