<?php

namespace App\Repositories;

use App\Models\FlashSale;

class FlashSaleRepository extends BaseRepository
{
    public function findFutureSales($date)
    {
        $time = strtotime($date);
        return FlashSale::query()
            ->where('published', 1)
            ->where('end_time', '>', $time)
            ->get();
    }

}
