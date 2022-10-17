<?php

namespace App\Console;

use App\Console\Commands\CleanLogTaskCommand;
use App\Console\Commands\CloseFlashSaleOrderTaskCommand;
use App\Console\Commands\CloseOrderTaskCommand;
use App\Console\Commands\CloseQuestionTaskCommand;
use App\Console\Commands\CloseTradeCommand;
use App\Console\Commands\DeliverTaskCommand;
use App\Console\Commands\NoticeTaskCommand;
use App\Console\Commands\PointGiftDeliverTaskCommand;
use App\Console\Commands\RefundTaskCommand;
use App\Console\Commands\RevokeVipTaskCommand;
use App\Console\Commands\ServerMonitorTaskCommand;
use App\Console\Commands\SitemapTaskCommand;
use App\Console\Commands\SyncAppInfoTaskCommand;
use App\Console\Commands\SyncArticleIndexTaskCommand;
use App\Console\Commands\SyncArticleScoreTaskCommand;
use App\Console\Commands\SyncCourseIndexTaskCommand;
use App\Console\Commands\SyncCourseScoreTaskCommand;
use App\Console\Commands\SyncCourseStatTaskCommand;
use App\Console\Commands\SyncGroupIndexTaskCommand;
use App\Console\Commands\SyncLearningTaskCommand;
use App\Console\Commands\SyncQuestionIndexTaskCommand;
use App\Console\Commands\SyncQuestionScoreTaskCommand;
use App\Console\Commands\SyncTagStatTaskCommand;
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
        SyncArticleScoreTaskCommand::class,
        SyncQuestionScoreTaskCommand::class,
        CleanLogTaskCommand::class,
        UnlockUserTaskCommand::class,
        RevokeVipTaskCommand::class,
        SyncAppInfoTaskCommand::class,
        SyncTagStatTaskCommand::class,
        SyncCourseStatTaskCommand::class,
        CloseQuestionTaskCommand::class,
        SitemapTaskCommand::class,
        TeacherLiveNoticeTaskCommand::class,
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
        $schedule->command('deliver_task')->everyMinute()->appendOutputTo( storage_path('logs/deliver_task.log'));
        $schedule->command('vod_event_task')->everyFiveMinutes()->appendOutputTo( storage_path('logs/vod_event_task.log'));
        $schedule->command('sync_learning_task')->cron('*/7 * * * *')->appendOutputTo( storage_path('logs/sync_learning_task.log'));
        $schedule->command('teacher_live_notice_task')->cron('*/9 * * * *')->appendOutputTo( storage_path('logs/teacher_live_notice_task.log'));
        $schedule->command('point_gift_deliver_task')->cron('*/11 * * * *')->appendOutputTo( storage_path('logs/point_gift_deliver_task.log'));
        $schedule->command('server_monitor_task')->cron('*/12 * * * *')->appendOutputTo( storage_path('logs/server_monitor_task.log'));
        $schedule->command('close_trade')->cron('*/12 * * * *')->appendOutputTo( storage_path('logs/close_trade.log'));
        $schedule->command('close_flash_sale_order')->everyFifteenMinutes()->appendOutputTo( storage_path('logs/close_flash_sale_order.log'));
        $schedule->command('notice_task')->everyMinute()->appendOutputTo( storage_path('logs/notice_task.log'));
        $schedule->command('close_order')->everyThreeHours()->appendOutputTo( storage_path('logs/close_order.log'));
        $schedule->command('refund_task')->cron('0 */7 * * *')->appendOutputTo( storage_path('logs/refund_task.log'));
        $schedule->command('sync_course_index_task')->cron('0 */11 * * *')->appendOutputTo( storage_path('logs/sync_course_index_task.log'));
//        $schedule->command('sync_group_index_task')->cron('0 */17 * * *')->appendOutputTo( storage_path('logs/sync_group_index_task.log'));
        $schedule->command('sync_user_index_task')->cron('0 */23 * * *')->appendOutputTo( storage_path('logs/sync_user_index_task.log'));
        $schedule->command('sync_article_index_task')->cron('0 */27 * * *')->appendOutputTo( storage_path('logs/sync_question_index_task.log'));
        $schedule->command('sync_question_index_task')->everyMinute()->appendOutputTo( storage_path('logs/sync_question_index_task.log'));
        $schedule->command('sync_course_score_task')->cron('0 */31 * * *')->appendOutputTo( storage_path('logs/sync_course_score_task.log'));
        $schedule->command('sync_article_score_task')->cron('0 */33 * * *')->appendOutputTo( storage_path('logs/sync_article_score_task.log'));
        $schedule->command('sync_question_score_task')->cron('0 */37 * * *')->appendOutputTo( storage_path('logs/sync_question_score_task.log'));
        $schedule->command('clean_log_task')->dailyAt('03:03')->appendOutputTo( storage_path('logs/clean_log_task.log'));
        $schedule->command('unlock_user_task')->dailyAt('03:07')->appendOutputTo( storage_path('logs/unlock_user_task.log'));
        $schedule->command('revoke_vip_task')->dailyAt('03:11')->appendOutputTo( storage_path('logs/revoke_vip_task.log'));
//        $schedule->command('sync_app_info_task')->dailyAt('03:13')->appendOutputTo( storage_path('logs/sync_app_info_task.log'));
        $schedule->command('sync_tag_stat_task')->dailyAt('03:17')->appendOutputTo( storage_path('logs/sync_tag_stat_task.log'));
        $schedule->command('sync_course_stat_task')->dailyAt('03:19')->appendOutputTo( storage_path('logs/sync_course_stat_task.log'));
        $schedule->command('close_question_task')->dailyAt('03:23')->appendOutputTo( storage_path('logs/close_question_task.log'));
//        $schedule->command('sitemap_task')->dailyAt('04:03');
//        $schedule->command('teacher_live_notice_task')->dailyAt('04:07');
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
