<?php

namespace App\Repositories;

use App\Models\Help;

class HelpRepository extends BaseRepository
{

    public function findById($id)
    {
        return Help::query()->find($id);
    }


}
