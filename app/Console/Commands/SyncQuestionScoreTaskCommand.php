<?php

namespace App\Console\Commands;

use App\Repositories\QuestionRepository;
use App\Services\Sync\QuestionScoreSyncService;
use App\Services\Utils\QuestionScoreService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncQuestionScoreTaskCommand extends Command
{
    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_question_score_task';

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

        $service = new QuestionScoreService();

        foreach ($questions as $question) {
            $service->handle($question);
        }

        $redis->sRem($key, ...$questionIds);
    }

    protected function getSyncKey()
    {
        $sync = new QuestionScoreSyncService();

        return $sync->getSyncKey();
    }

}
