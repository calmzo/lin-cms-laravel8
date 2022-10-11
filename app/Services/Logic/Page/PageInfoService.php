<?php

namespace App\Services\Logic\Page;

use App\Models\Page;
use App\Services\Logic\PageTrait;
use App\Services\Logic\LogicService;

class PageInfoService extends LogicService
{

    use PageTrait;

    public function handle($id)
    {
        $page = $this->checkPage($id);

        return $this->handlePage($page);
    }

    protected function handlePage(Page $page)
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'content' => $page->content,
            'published' => $page->published,
            'deleted' => $page->deleted,
            'create_time' => $page->create_time,
            'update_time' => $page->update_time,
        ];
    }

}
