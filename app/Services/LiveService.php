<?php

namespace App\Services;

use App\Services\Logic\Live\LiveChapterService;
use App\Services\Logic\Live\LiveListService;

class LiveService extends BaseService
{
    public function getLives($params)
    {
        $service = new LiveListService();

        $pager = $service->handle($params);

        return $pager;

    }

    public function getLiveChats($id)
    {
        $service = new LiveChapterService();

        $chats = $service->getRecentChats($id);

        return ['chats' => $chats];

    }

}
