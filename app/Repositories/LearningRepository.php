<?php

namespace App\Repositories;

use App\Models\Learning;

class LearningRepository extends BaseRepository
{
    public function findByRequestId($requestId)
    {
        return Learning::query()->where('request_id', $requestId)->first();
    }

}
