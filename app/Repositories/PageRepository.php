<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository extends BaseRepository
{
    public function findById($id)
    {
        return Page::query()->find($id);
    }

    public function findByAlias($alias)
    {
        return Page::query()->where('alias', $alias)->first();
    }
}
