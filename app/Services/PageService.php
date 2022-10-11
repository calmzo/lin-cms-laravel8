<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Services\Logic\Page\PageInfoService;

class PageService extends BaseService
{
    public function getPage($id)
    {
        $service = new PageInfoService();

        $page = $service->handle($id);
        if ($page['published'] == 0) {
            throw new NotFoundException();
        }

        return ['page' => $page];
    }

}
