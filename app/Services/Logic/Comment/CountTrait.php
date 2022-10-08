<?php

namespace App\Services\Logic\Comment;

use App\Events\UserDailyCounterIncrCommentCountEvent;
use App\Models\Answer;
use App\Models\Article;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Question;
use App\Models\User;
use App\Repositories\ChapterRepository;

trait CountTrait
{

    protected function incrItemCommentCount($item)
    {
        if ($item instanceof Chapter) {
            $this->incrChapterCommentCount($item);
        } elseif ($item instanceof Article) {
            $this->incrArticleCommentCount($item);
        } elseif ($item instanceof Question) {
            $this->incrQuestionCommentCount($item);
        } elseif ($item instanceof Answer) {
            $this->incrAnswerCommentCount($item);
        }
    }

    protected function decrItemCommentCount($item)
    {
        if ($item instanceof Chapter) {
            $this->decrChapterCommentCount($item);
        } elseif ($item instanceof Article) {
            $this->decrArticleCommentCount($item);
        } elseif ($item instanceof Question) {
            $this->decrQuestionCommentCount($item);
        } elseif ($item instanceof Answer) {
            $this->decrAnswerCommentCount($item);
        }
    }

    protected function incrCommentReplyCount(Comment $comment)
    {
        $comment->reply_count += 1;

        $comment->save();
    }

    protected function incrChapterCommentCount(Chapter $chapter)
    {
        $chapter->comment_count += 1;

        $chapter->save();

        if ($chapter->parent_id > 0) {
            $parent = $this->findChapter($chapter->parent_id);

            $parent->comment_count += 1;

            $parent->save();
        }

    }

    protected function incrArticleCommentCount(Article $article)
    {
        $article->comment_count += 1;

        $article->save();
    }

    protected function incrQuestionCommentCount(Question $question)
    {
        $question->comment_count += 1;

        $question->save();
    }

    protected function incrAnswerCommentCount(Answer $answer)
    {
        $answer->comment_count += 1;

        $answer->save();
    }

    protected function incrUserCommentCount(User $user)
    {
        $user->comment_count += 1;

        $user->save();
    }

    protected function decrCommentReplyCount(Comment $comment)
    {
        if ($comment->reply_count > 0) {
            $comment->reply_count -= 1;
            $comment->save();
        }
    }

    protected function decrChapterCommentCount(Chapter $chapter)
    {
        if ($chapter->comment_count > 0) {
            $chapter->comment_count -= 1;
            $chapter->save();
        }

        $parent = $this->findChapter($chapter->parent_id);

        if ($parent->comment_count > 0) {
            $parent->comment_count -= 1;
            $parent->save();
        }
    }

    protected function decrArticleCommentCount(Article $article)
    {
        if ($article->comment_count > 0) {
            $article->comment_count -= 1;
            $article->save();
        }
    }

    protected function decrQuestionCommentCount(Question $question)
    {
        if ($question->comment_count > 0) {
            $question->comment_count -= 1;
            $question->save();
        }
    }

    protected function decrAnswerCommentCount(Answer $answer)
    {
        if ($answer->comment_count > 0) {
            $answer->comment_count -= 1;
            $answer->save();
        }
    }

    protected function decrUserCommentCount(User $user)
    {
        if ($user->comment_count > 0) {
            $user->comment_count -= 1;
            $user->save();
        }
    }

    protected function incrUserDailyCommentCount(User $user)
    {
        UserDailyCounterIncrCommentCountEvent::dispatch($user);
    }

    protected function findChapter($id)
    {
        $chapterRepo = new ChapterRepository();

        return $chapterRepo->findById($id);
    }

}
