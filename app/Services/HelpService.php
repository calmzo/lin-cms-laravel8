<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Services\Logic\Help\HelpInfoService;
use App\Services\Logic\Help\HelpListService;

class HelpService extends BaseService
{
    public function getHelps()
    {

        $service = new HelpListService();

        $helps = $service->handle();

        return ['helps' => $helps];
    }

    public function getHelp($id)
    {
        $service = new HelpInfoService();

        $help = $service->handle($id);

        if ($help['published'] == 0) {
            throw new NotFoundException();
        }

        return ['help' => $help];
    }

}
