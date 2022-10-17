<?php

namespace App\Console\Commands;

use App\Models\Question;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CloseQuestionTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close_question_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '关闭待关闭问题';

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
        $questions = $this->findQuestions();

        echo sprintf('pending questions: %s', $questions->count()) . PHP_EOL;

        if ($questions->count() == 0) return;

        echo '------ start close question task ------' . PHP_EOL;

        foreach ($questions as $question) {
            $question->closed = 1;
            $question->update();
        }

        echo '------ end close question task ------' . PHP_EOL;
    }

    /**
     * 查找待关闭问题
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findQuestions($limit = 1000)
    {
        $time = Carbon::now()->subDays(7);
        echo $time . PHP_EOL;

        return Question::query()
            ->where('create_time', '<', $time)
            ->where('answer_count', 0)
            ->where('closed', 0)
            ->limit($limit)
            ->get();
    }
}
