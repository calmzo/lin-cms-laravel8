<?php

namespace App\Traits;

use App\Models\Answer;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Question;

trait ReportCountTrait
{

    protected function handleItemReportCount($item)
    {
        if ($item instanceof Article) {
            $this->incrArticleReportCount($item);
        } elseif ($item instanceof Question) {
            $this->incrQuestionReportCount($item);
        } elseif ($item instanceof Answer) {
            $this->incrAnswerReportCount($item);
        } elseif ($item instanceof Comment) {
            $this->incrCommentReportCount($item);
        }
    }

    protected function incrArticleReportCount(Article $article)
    {
        $article->report_count += 1;

        $article->save();
    }

    protected function incrQuestionReportCount(Question $question)
    {
        $question->report_count += 1;

        $question->save();
    }

    protected function incrAnswerReportCount(Answer $answer)
    {
        $answer->report_count += 1;

        $answer->save();
    }

    protected function incrCommentReportCount(Comment $comment)
    {
        $comment->report_count += 1;

        $comment->save();
    }

}
