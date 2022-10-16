<?php

namespace App\Console;

use App\Console\Commands\CleanLogTaskCommand;
use App\Console\Commands\CloseOrderTaskCommand;
use App\Console\Commands\CloseTradeCommand;
use App\Console\Commands\DeliverTaskCommand;
use App\Console\Commands\NoticeTaskCommand;
use App\Console\Commands\RefundTaskCommand;
use App\Console\Commands\ServerMonitorTaskCommand;
use App\Console\Commands\SyncLearningTaskCommand;
use App\Console\Commands\UnlockUserTaskCommand;
use App\Console\Commands\VodEventTakCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DeliverTaskCommand::class,
        VodEventTakCommand::class,
        SyncLearningTaskCommand::class,
        CloseTradeCommand::class,
        RefundTaskCommand::class,
        CleanLogTaskCommand::class,
        CloseOrderTaskCommand::class,
        ServerMonitorTaskCommand::class,
        NoticeTaskCommand::class,
        UnlockUserTaskCommand::class,
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('deliver_task')->everyMinute();
        $schedule->command('vod_event_task')->everyFiveMinutes();
        $schedule->command('sync_learning_task')->cron('*/7 * * * *');
//        $schedule->command('close_trade')->everyMinute();
//        $schedule->command('refund_task')->everyMinute();
//        $schedule->command('command:clean_log_task')->monthly();
//        $schedule->command('command:close_order')->daily();
//        $schedule->command('command:server_monitor_task')->daily();
//        $schedule->command('command:notice_task')->daily();
        $schedule->command('command:unlock_user_task')->everySixHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
