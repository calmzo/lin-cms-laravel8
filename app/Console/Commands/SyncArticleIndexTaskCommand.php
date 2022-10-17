<?php

namespace App\Console\Commands;

use App\Repositories\ArticleRepository;
use App\Services\Sync\ArticleIndexSyncService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncArticleIndexTaskCommand extends Command
{
    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_article_index_task';

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

//        $document = new ArticleDocument();
//
//        $handler = new ArticleSearcher();
//
//        $index = $handler->getXS()->getIndex();
//
//        $index->openBuffer();
//
//        foreach ($articles as $article) {
//
//            $doc = $document->setDocument($article);
//
//            if ($article->published == ArticleModel::PUBLISH_APPROVED) {
//                $index->update($doc);
//            } else {
//                $index->del($article->id);
//            }
//        }
//
//        $index->closeBuffer();

        $redis->srem($key, ...$articleIds);
    }

    protected function getSyncKey()
    {
        $sync = new ArticleIndexSyncService();

        return $sync->getSyncKey();
    }
}
