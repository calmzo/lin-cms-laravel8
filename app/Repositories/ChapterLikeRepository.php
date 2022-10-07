<?php

namespace App\Repositories;

use App\Models\ChapterLike;

class ChapterLikeRepository extends BaseRepository
{
    public function findChapterLike($chapterId, $userId)
    {
        return ChapterLike::withTrashed()->where('chapter_id', $chapterId)->where('user_id', $userId)->first();
    }

}
