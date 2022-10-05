<?php

namespace App\Caches;

use App\Models\Slide;
use App\Models\Slide as SlideModel;

class IndexSlideListCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_slide_list';
    }

    public function getContent($id = null)
    {
        $limit = 5;

        $slides = $this->findSlides($limit);
        if ($slides->count() == 0) {
            return [];
        }

        return $this->handleContent($slides);
    }

    /**
     * @param SlideModel[] $slides
     * @return array
     */
    protected function handleContent($slides)
    {
        $result = [];

        foreach ($slides as $slide) {
            $result[] = [
                'id' => $slide->id,
                'title' => $slide->title,
                'cover' => $slide->cover,
                'target' => $slide->target,
                'content' => $slide->content,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findSlides($limit = 5)
    {
        return Slide::query()
            ->where('published', 1)
            ->orderBy('priority')
            ->limit($limit)
            ->get();
    }

}
