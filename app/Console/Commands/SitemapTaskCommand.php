<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SitemapTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap_task';

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
        echo "------ sitemap_task todo ------" . PHP_EOL;
//        $this->siteUrl = $this->getSiteUrl();
//
//        $this->sitemap = new Sitemap();
//
//        $filename = tmp_path('sitemap.xml');
//
//        $this->addIndex();
//        $this->addCourses();
//        $this->addArticles();
//        $this->addQuestions();
//        $this->addTeachers();
//        $this->addTopics();
//        $this->addHelps();
//        $this->addPages();
//        $this->addOthers();
//
//        $this->sitemap->build($filename);
    }

    protected function getSiteUrl()
    {
        $service = new AppService();

        $settings = $service->getSettings('site');

        return $settings['url'] ?? '';
    }

    protected function addIndex()
    {
        $this->sitemap->addItem($this->siteUrl, 1);
    }

    protected function addCourses()
    {
        /**
         * @var Resultset|CourseModel[] $courses
         */
        $courses = CourseModel::query()
            ->where('published = 1')
            ->orderBy('id DESC')
            ->limit(500)
            ->execute();

        if ($courses->count() == 0) return;

        foreach ($courses as $course) {
            $loc = sprintf('%s/course/%s', $this->siteUrl, $course->id);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addArticles()
    {
        /**
         * @var Resultset|ArticleModel[] $articles
         */
        $articles = ArticleModel::query()
            ->where('published = :published:', ['published' => ArticleModel::PUBLISH_APPROVED])
            ->orderBy('id DESC')
            ->limit(500)
            ->execute();

        if ($articles->count() == 0) return;

        foreach ($articles as $article) {
            $loc = sprintf('%s/article/%s', $this->siteUrl, $article->id);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addQuestions()
    {
        /**
         * @var Resultset|QuestionModel[] $questions
         */
        $questions = QuestionModel::query()
            ->where('published = :published:', ['published' => QuestionModel::PUBLISH_APPROVED])
            ->orderBy('id DESC')
            ->limit(500)
            ->execute();

        if ($questions->count() == 0) return;

        foreach ($questions as $question) {
            $loc = sprintf('%s/question/%s', $this->siteUrl, $question->id);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addTeachers()
    {
        /**
         * @var Resultset|UserModel[] $teachers
         */
        $teachers = UserModel::query()->where('edu_role = 2')->execute();

        if ($teachers->count() == 0) return;

        foreach ($teachers as $teacher) {
            $loc = sprintf('%s/teacher/%s', $this->siteUrl, $teacher->id);
            $this->sitemap->addItem($loc, 0.6);
        }
    }

    protected function addTopics()
    {
        /**
         * @var Resultset|TopicModel[] $topics
         */
        $topics = TopicModel::query()->where('published = 1')->execute();

        if ($topics->count() == 0) return;

        foreach ($topics as $topic) {
            $loc = sprintf('%s/topic/%s', $this->siteUrl, $topic->id);
            $this->sitemap->addItem($loc, 0.6);
        }
    }

    protected function addPages()
    {
        /**
         * @var Resultset|PageModel[] $pages
         */
        $pages = PageModel::query()->where('published = 1')->execute();

        if ($pages->count() == 0) return;

        foreach ($pages as $page) {
            $loc = sprintf('%s/page/%s', $this->siteUrl, $page->id);
            $this->sitemap->addItem($loc, 0.7);
        }
    }

    protected function addHelps()
    {
        /**
         * @var Resultset|HelpModel[] $helps
         */
        $helps = HelpModel::query()->where('published = 1')->execute();

        if ($helps->count() == 0) return;

        foreach ($helps as $help) {
            $loc = sprintf('%s/help/%s', $this->siteUrl, $help->id);
            $this->sitemap->addItem($loc, 0.7);
        }
    }

    protected function addOthers()
    {
        $this->sitemap->addItem("{$this->siteUrl}/course/list", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/teacher/list", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/vip", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/help", 0.6);
    }

}
