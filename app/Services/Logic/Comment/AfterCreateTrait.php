<?php

namespace App\Services\Logic\Comment;

use App\Enums\CommentEnums;
use App\Models\Answer;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Question;
use App\Lib\Notice\System\AnswerCommentedNotice;
use App\Lib\Notice\System\ArticleCommentedNotice;
use App\Lib\Notice\System\CommentRepliedNotice;
use App\Lib\Notice\System\QuestionCommentedNotice;
use App\Services\Logic\Point\History\CommentPostPointHistory;

trait AfterCreateTrait
{

    use CountTrait;

    protected function handleItemCommentedNotice($item, Comment $comment)
    {
        if ($item instanceof Article) {
            if ($comment->user_id != $item->user_id) {
                $this->handleArticleCommentedNotice($item, $comment);
            }
        } elseif ($item instanceof Question) {
            if ($comment->user_id != $item->user_id) {
                $this->handleQuestionCommentedNotice($item, $comment);
            }
        } elseif ($item instanceof Answer) {
            if ($comment->user_id != $item->user_id) {
                $this->handleAnswerCommentedNotice($item, $comment);
            }
        }
    }

    protected function handleArticleCommentedNotice(Article $article, Comment $comment)
    {
        $notice = new ArticleCommentedNotice();

        $notice->handle($article, $comment);
    }

    protected function handleQuestionCommentedNotice(Question $question, Comment $comment)
    {
        $notice = new QuestionCommentedNotice();

        $notice->handle($question, $comment);
    }

    protected function handleAnswerCommentedNotice(Answer $answer, Comment $comment)
    {
        $notice = new AnswerCommentedNotice();

        $notice->handle($answer, $comment);
    }

    protected function handleCommentRepliedNotice(Comment $reply)
    {
        $notice = new CommentRepliedNotice();

        $notice->handle($reply);
    }

    protected function handleCommentPostPoint(Comment $comment)
    {
        if ($comment->published != CommentEnums::PUBLISH_APPROVED) return;

        $service = new CommentPostPointHistory();

        $service->handle($comment);
    }

}
