<?php

namespace App\Repositories;

use App\Models\QuestionLike;

class QuestionLikeRepository extends BaseRepository
{
    public function findQuestionLike($questionId, $userId)
    {
        return QuestionLike::withTrashed()->where('question_id', $questionId)->where('user_id', $userId)->first();
    }

}
