<?php

namespace App\Services\Logic\Help;

use App\Models\Help;
use App\Traits\HelpTrait;
use App\Services\Logic\LogicService;

class HelpInfoService extends LogicService
{

    use HelpTrait;

    public function handle($id)
    {
        $help = $this->checkHelp($id);

        return $this->handleHelp($help);
    }

    protected function handleHelp(Help $help)
    {
        return [
            'id' => $help->id,
            'title' => $help->title,
            'content' => $help->content,
            'published' => $help->published,
            'deleted' => $help->deleted,
            'create_time' => $help->create_time,
            'update_time' => $help->update_time,
        ];
    }

}
