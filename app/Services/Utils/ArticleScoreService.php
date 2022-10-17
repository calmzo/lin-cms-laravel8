<?php

namespace App\Services\Utils;

use App\Enums\ArticleEnums;
use App\Models\Article;
use App\Services\BaseService;

class ArticleScoreService extends BaseService
{

    public function handle(Article $article)
    {
       return $this->calculateScore($article);
    }

    protected function calculateScore(Article $article)
    {
        $weight = [
            'factor1' => 0.2,
            'factor2' => 0.1,
            'factor3' => 0.15,
            'factor4' => 0.2,
            'factor5' => 0.15,
            'factor6' => 0.2,
        ];

        $items = [
            'factor1' => 0.0,
            'factor2' => 0.0,
            'factor3' => 0.0,
            'factor4' => 0.0,
            'factor5' => 0.0,
            'factor6' => 0.0,
        ];

        if ($article->featured == 1) {
            $items['factor1'] =  7 * $weight['factor1'];
        }

        if ($article->source_type == ArticleEnums::SOURCE_ORIGIN) {
            $items['factor2'] = 5 * $weight['factor2'];
        }

        if ($article->view_count > 0) {
            $items['factor3'] = log($article->view_count) * $weight['factor3'];
        }

        if ($article->favorite_count > 0) {
            $items['factor4'] = log($article->favorite_count) * $weight['factor4'];
        }

        if ($article->like_count > 0) {
            $items['factor5'] = log($article->like_count) * $weight['factor5'];
        }

        if ($article->comment_count > 0 ) {
            $items['factor6'] = log($article->comment_count) * $weight['factor6'];
        }

        $score = array_sum($items) / log(time() - $article->create_time);

        return round($score, 4);
    }

}
