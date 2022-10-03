<?php

namespace App\Repositories;

use App\Models\Admin\LinLog;

class LinLogRepository extends BaseRepository
{
    public function findById($id)
    {
        return LinLog::query()->find($id);
    }
}
