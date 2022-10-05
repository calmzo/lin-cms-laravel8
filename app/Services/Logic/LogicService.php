<?php
namespace App\Services\Logic;

use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LogicService extends BaseService
{
    public function newPaginator(LengthAwarePaginator $page, array $items): object
    {
        return (Object)[
            'total' => $page->total(),
            'currentPage' => $page->currentPage(),
            'perPage' => $page->perPage(),
            'items' => $items
        ];
    }

}
