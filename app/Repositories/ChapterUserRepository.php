<?php

namespace App\Repositories;

use App\Models\ChapterUser;

class ChapterUserRepository extends BaseRepository
{

    public function findPlanChapterUser($chapterId, $userId, $planId)
    {
        return ChapterUser::query()
            ->where('chapter_id', $chapterId)
            ->where('user_id', $userId)
            ->where('plan_id', $planId)
            ->first();
    }
}
