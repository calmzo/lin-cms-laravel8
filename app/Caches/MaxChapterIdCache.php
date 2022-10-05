<?php

namespace App\Caches;
use App\Models\Chapter;

class MaxChapterIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_chapter_id';
    }

    public function getContent($id = null)
    {
        $chapter = Chapter::query()->latest('id')->first();

        return $chapter->id ?? 0;
    }

}
