<?php

namespace App\Console\Commands;

use App\Lib\Notice\DingTalk\TeacherLiveNotice;
use App\Models\ChapterLive;
use App\Repositories\ChapterLiveRepository;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class TeacherLiveNoticeTaskCommand extends Command
{
    use ServiceTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teacher_live_notice_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '消费讲师提醒';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //消费讲师提醒
        $redis = $this->getRedis();

        $keyName = $this->getCacheKeyName();

        $liveIds = $redis->sMembers($keyName);

        if (count($liveIds) == 0) return;

        $liveRepo = new ChapterLiveRepository();

        $notice = new TeacherLiveNotice();

        foreach ($liveIds as $liveId) {

            $live = $liveRepo->findById($liveId);

            if ($live->start_time - time() < 30 * 60) {

                $notice->createTask($live);

                $redis->sRem($keyName, $liveId);
            }
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findLives()
    {
        $today = strtotime(date('Ymd'));
        $end = $today + 86400;
        return ChapterLive::query()
            ->whereBetween('start_time', [$today, $end])
            ->get();
    }

    protected function getCacheKeyName()
    {
        return 'teacher_live_notice_task';
    }
}
