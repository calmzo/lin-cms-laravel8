<?php

namespace App\Services\Logic\Chapter;

use App\Builders\ResourceListBuilder;
use App\Repositories\ResourceRepository;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\LogicService;

class ResourceListService extends LogicService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $resourceRepo = new ResourceRepository();

        $resources = $resourceRepo->findByChapterId($chapter->id);

        if ($resources->count() == 0) {
            return [];
        }

        $builder = new ResourceListBuilder();

        $relations = $resources->toArray();

        return $builder->getUploads($relations);
    }

}
