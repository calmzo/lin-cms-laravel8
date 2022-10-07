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

    public function getLiveStats($id)
    {
        $service = new LiveChapterService();

        $stats = $service->getStats($id);

        return ['stats' => $stats];

    }


    public function getLiveStatus($id)
    {
        $service = new LiveChapterService();

        $status = $service->getStatus($id);

        return ['status' => $status];

    }


    public function bindUser($id, $clientId)
    {
        $service = new LiveChapterService();

        $service->bindUser($id, $clientId);

        return true;

    }

    public function sendMsg($id, $content)
    {
        $service = new LiveChapterService();

        $message = $service->sendMessage($id, $content);
        return ['message' => $message];

    }

}