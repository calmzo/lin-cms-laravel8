<?php

namespace App\Console\Commands;

use App\Repositories\CourseRepository;
use App\Services\Sync\CourseIndexSyncService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncCourseIndexTaskCommand extends Command
{
    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_course_index_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $courseIds = $redis->srandmember($key, 1000);

        if (!$courseIds) return;

        $courseRepo = new CourseRepository();

        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) return;

//        $document = new CourseDocument();
//
//        $handler = new CourseSearcher();
//
//        $index = $handler->getXS()->getIndex();
//
//        $index->openBuffer();
//
//        foreach ($courses as $course) {
//
//            $doc = $document->setDocument($course);
//
//            if ($course->published == 1) {
//                $index->update($doc);
//            } else {
//                $index->del($course->id);
//            }
//        }
//
//        $index->closeBuffer();

        $redis->srem($key, ...$courseIds);
    }

    protected function getSyncKey()
    {
        $sync = new CourseIndexSyncService();

        return $sync->getSyncKey();
    }
}
