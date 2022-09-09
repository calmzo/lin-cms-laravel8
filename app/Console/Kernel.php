<?php

namespace App\Console;

use App\Console\Commands\CleanLogTaskCommand;
use App\Console\Commands\CloseTradeCommand;
use App\Console\Commands\DeliverTaskCommand;
use App\Console\Commands\RefundTaskCommand;
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
        Commands\RepositoryMakeCommand::class,
        CloseTradeCommand::class,
        DeliverTaskCommand::class,
        RefundTaskCommand::class,
        CleanLogTaskCommand::class,
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('close_trade')->everyMinute();
//        $schedule->command('deliver_task')->everyMinute();
//        $schedule->command('refund_task')->everyMinute();
        $schedule->command('command:clean_log_task')->monthly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
