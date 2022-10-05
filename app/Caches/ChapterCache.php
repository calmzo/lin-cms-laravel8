<?php

namespace App\Caches;

use App\Repositories\ChapterRepository;

class ChapterCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "chapter:{$id}";
    }

    public function getContent($id = null)
    {
        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findById($id);

        return $chapter ?: null;
    }

}
