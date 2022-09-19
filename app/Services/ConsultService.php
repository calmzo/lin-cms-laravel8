<?php

namespace App\Services;

use App\Enums\ConsultEnums;
use App\Models\Consult;

class ConsultService
{

    public function countConsults()
    {
        return Consult::query()->where('published', ConsultEnums::PUBLISH_APPROVED)->count();
    }
}
