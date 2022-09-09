<?php

namespace App\Lib\Search;

class QuestionSearcher extends Searcher
{

    public function __construct()
    {
        $this->xs = $this->getXS();
    }

    public function getXS()
    {
        $filename = config_path('xs.question.ini');

        return new \XS($filename);
    }

    public function getHighlightFields()
    {
        return ['title', 'summary'];
    }

}
