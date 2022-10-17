<?php

namespace App\Console\Commands;

use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Console\Command;

class SyncTagStatTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_tag_stat_task';

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
        $tags = $this->findTags();

        echo sprintf('pending tags: %s', $tags->count()) . PHP_EOL;

        if ($tags->count() == 0) return;

        echo '------ start sync tag stat task ------' . PHP_EOL;

        foreach ($tags as $tag) {
            $this->recountTaggedItems($tag);
        }

        echo '------ end sync tag stat task ------' . PHP_EOL;
    }

    protected function recountTaggedItems(Tag $tag)
    {
        $tagRepo = new TagRepository();

        $tag->follow_count = $tagRepo->countFollows($tag->id);
        $tag->course_count = $tagRepo->countCourses($tag->id);
        $tag->article_count = $tagRepo->countArticles($tag->id);
        $tag->question_count = $tagRepo->countQuestions($tag->id);

        $tag->update();
    }

    protected function findTags()
    {
        return Tag::query()
            ->where('published', 1)
            ->get();
    }
}
