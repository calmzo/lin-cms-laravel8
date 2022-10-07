<?php

namespace App\Repositories;

use App\Models\Resource;

class ResourceRepository extends BaseRepository
{
    public function findByChapterId($chapterId)
    {
        return Resource::query()
            ->where('chapter_id', $chapterId)
            ->get();
    }

}
