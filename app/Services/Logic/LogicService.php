<?php

namespace App\Services\Logic;

use App\Services\BaseService;
use App\Traits\ServiceTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LogicService extends BaseService
{

    use ServiceTrait;

    public function newPaginator(LengthAwarePaginator $page, array $items): object
    {
        return (object)[
            'total' => $page->total(),
            'currentPage' => $page->currentPage(),
            'perPage' => $page->perPage(),
            'items' => $items
        ];
    }

}
