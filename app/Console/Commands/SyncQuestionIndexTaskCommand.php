<?php

namespace App\Console\Commands;

use App\Repositories\QuestionRepository;
use App\Services\Sync\QuestionIndexSyncService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncQuestionIndexTaskCommand extends Command
{
    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_question_index_task';

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

        $questionIds = $redis->srandmember($key, 1000);

        if (!$questionIds) return;

        $questionRepo = new QuestionRepository();

        $questions = $questionRepo->findByIds($questionIds);

        if ($questions->count() == 0) return;

//        $document = new QuestionDocument();
//
//        $handler = new QuestionSearcher();
//
//        $index = $handler->getXS()->getIndex();
//
//        $index->openBuffer();
//
//        foreach ($questions as $question) {
//
//            $doc = $document->setDocument($question);
//
//            if ($question->published == QuestionModel::PUBLISH_APPROVED) {
//                $index->update($doc);
//            } else {
//                $index->del($question->id);
//            }
//        }
//
//        $index->closeBuffer();

        $redis->srem($key, ...$questionIds);
    }

    protected function getSyncKey()
    {
        $sync = new QuestionIndexSyncService();

        return $sync->getSyncKey();
    }
}
