<?php

namespace App\Services\Admin;

use App\Traits\ServiceTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BaseService
{
    use ServiceTrait;

    public function newPaginator(LengthAwarePaginator $page, array $items): object
    {

        return (Object)[
            'total' => $page->total(),
            'page' => $page->currentPage() - 1,
            'count' => $page->perPage(),
            'items' => $items
        ];

    }
}
