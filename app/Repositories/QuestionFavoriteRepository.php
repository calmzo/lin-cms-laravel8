<?php

namespace App\Repositories;

use App\Models\QuestionFavorite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionFavoriteRepository extends BaseRepository
{
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15): LengthAwarePaginator
    {
        $query = QuestionFavorite::query();

        if (!empty($where['question_id'])) {
            $query->where('question_id', $where['question_id']);
        }

        if (!empty($where['user_id'])) {
            $query->where('user_id', $where['user_id']);
        }

        switch ($sort) {
            default:
                $query->orderByDesc('id');
                break;
        }


        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function findQuestionFavorite($questionId, $userId)
    {
        return QuestionFavorite::withTrashed()->where('question_id', $questionId)->where('user_id', $userId)->first();
    }

}
