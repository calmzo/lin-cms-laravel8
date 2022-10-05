<?php

namespace App\Http\Controllers\V1;

use App\Caches\IndexArticleListCache;
use App\Caches\IndexFlashSaleListCache;
use App\Caches\IndexLiveListCache;
use App\Caches\IndexQuestionListCache;
use App\Caches\IndexSlideListCache;
use App\Caches\IndexTeacherListCache;

class IndexController extends BaseController
{
    //
    protected $only = [];

    public function getSlides()
    {
        $cache = new IndexSlideListCache();

        $slides = $cache->get();

        return $this->success(['slides' => $slides]);

    }

    public function getArticles()
    {
        $cache = new IndexArticleListCache();

        $articles = $cache->get();

        return $this->success(['articles' => $articles]);

    }

    public function getQuestions()
    {
        $cache = new IndexQuestionListCache();

        $questions = $cache->get();

        return $this->success(['questions' => $questions]);

    }

    public function getLives()
    {
        $cache = new IndexLiveListCache();

        $lives = $cache->get();

        return $this->success(['lives' => $lives]);

    }

    public function getTeachers()
    {
        $cache = new IndexTeacherListCache();

        $teachers = $cache->get();

        return $this->success(['teachers' => $teachers]);

    }

    public function getFalshSales()
    {
        $cache = new IndexFlashSaleListCache();

        $sales = $cache->get();

        return $this->success(['sales' => $sales]);

    }
}
