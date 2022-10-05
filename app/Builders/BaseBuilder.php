<?php

namespace App\Builders;

use App\Repositories\QuestionRepository;
use App\Services\QuestionService;
use App\Services\UserService;

class BaseBuilder
{
    public function objects(array $items)
    {
        return kg_array_object($items);
    }

}
