<?php

namespace App\Http\Controllers\V1;

use App\Services\HelpService;

class HelpController extends BaseController
{
    //
    protected $only = [];

    public function getHelps()
    {
        $service = new HelpService();
        $helps = $service->getHelps();
        return $this->success($helps);

    }

    public function getHelp($id)
    {
        $service = new HelpService();
        $help = $service->getHelp($id);
        return $this->success($help);

    }
}
