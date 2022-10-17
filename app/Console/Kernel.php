<?php

namespace App\Console;

use App\Console\Commands\CleanLogTaskCommand;
use App\Console\Commands\CloseFlashSaleOrderTaskCommand;
use App\Console\Commands\CloseOrderTaskCommand;
use App\Console\Commands\CloseTradeCommand;
use App\Console\Commands\DeliverTaskCommand;
use App\Console\Commands\NoticeTaskCommand;
use App\Console\Commands\PointGiftDeliverTaskCommand;
use App\Console\Commands\RefundTaskCommand;
use App\Console\Commands\ServerMonitorTaskCommand;
use App\Console\Commands\SyncArticleIndexTaskCommand;
use App\Console\Commands\SyncCourseIndexTaskCommand;
use App\Console\Commands\SyncCourseScoreTaskCommand;
use App\Console\Commands\SyncGroupIndexTaskCommand;
use App\Console\Commands\SyncLearningTaskCommand;
use App\Console\Commands\SyncQuestionIndexTaskCommand;
use App\Console\Commands\SyncUserIndexTaskCommand;
use App\Console\Commands\TeacherLiveNoticeTaskCommand;
use App\Console\Commands\UnlockUserTaskCommand;
use App\Console\Commands\VodEventTakCommand;
use App\Console\Tasks\TeacherLiveNoticeTask;
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
        TeacherLiveNoticeTaskCommand::class,
        PointGiftDeliverTaskCommand::class,
        ServerMonitorTaskCommand::class,
        CloseTradeCommand::class,
        CloseFlashSaleOrderTaskCommand::class,
        NoticeTaskCommand::class,
        CloseOrderTaskCommand::class,
        RefundTaskCommand::class,
        SyncCourseIndexTaskCommand::class,
        SyncGroupIndexTaskCommand::class,
        SyncUserIndexTaskCommand::class,
        SyncArticleIndexTaskCommand::class,
        SyncQuestionIndexTaskCommand::class,
        SyncCourseScoreTaskCommand::class,
        CleanLogTaskCommand::class,
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
        $schedule->command('teacher_live_notice_task')->cron('*/9 * * * *');
        $schedule->command('point_gift_deliver_task')->cron('*/11 * * * *');
        $schedule->command('server_monitor_task')->cron('*/12 * * * *');
        $schedule->command('close_trade')->cron('*/12 * * * *');
        $schedule->command('close_flash_sale_order')->everyFifteenMinutes();
        $schedule->command('notice_task')->everyMinute();
        $schedule->command('close_order')->everyThreeHours();
        $schedule->command('refund_task')->cron('0 */7 * * *');
        $schedule->command('sync_course_index_task')->cron('0 */11 * * *');
        $schedule->command('sync_group_index_task')->cron('0 */17 * * *');
        $schedule->command('sync_user_index_task')->cron('0 */23 * * *');
        $schedule->command('sync_article_index_task')->cron('0 */27 * * *');
        $schedule->command('sync_question_index_task')->cron('0 */29 * * *');
        $schedule->command('sync_course_score_task')->cron('0 */31 * * *');
//        $schedule->command('command:clean_log_task')->monthly();
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
