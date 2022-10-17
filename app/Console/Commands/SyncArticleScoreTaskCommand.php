<?php

namespace App\Console\Commands;

use App\Repositories\ArticleRepository;
use App\Services\Sync\ArticleScoreSyncService;
use App\Services\Utils\ArticleScoreService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncArticleScoreTaskCommand extends Command
{
    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_article_score_task';

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

        $articleIds = $redis->srandmember($key, 1000);

        if (!$articleIds) return;

        $articleRepo = new ArticleRepository();

        $articles = $articleRepo->findByIds($articleIds);

        if ($articles->count() == 0) return;

        $service = new ArticleScoreService();

        foreach ($articles as $article) {
            $service->handle($article);
        }

        $redis->sRem($key, ...$articleIds);
    }

    protected function getSyncKey()
    {
        $sync = new ArticleScoreSyncService();

        return $sync->getSyncKey();
    }
}
