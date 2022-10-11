<?php

namespace App\Caches;

use App\Models\FlashSale;

class MaxFlashSaleIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_flash_sale_id';
    }

    public function getContent($id = null)
    {
        $sale = FlashSale::query()->latest('id')->first();

        return $sale->id ?? 0;
    }

}
