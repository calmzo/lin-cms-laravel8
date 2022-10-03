<?php

namespace App\Repositories;

use App\Models\Chapter;

class ChapterRepository extends BaseRepository
{
    public function findByIds($ids, $columns = '*')
    {
        return Chapter::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }

}
