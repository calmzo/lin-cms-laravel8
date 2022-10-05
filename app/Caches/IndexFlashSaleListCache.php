<?php

namespace App\Caches;

use App\Services\Logic\FlashSale\SaleList;
use App\Services\Logic\FlashSale\SaleListService;

class IndexFlashSaleListCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return strtotime('tomorrow') - time();
    }

    public function getKey($id = null)
    {
        return 'index_flash_sale_list';
    }

    public function getContent($id = null)
    {
        $service = new SaleListService();

        $sales = $service->handle();

        return $sales[0]['items'] ?? [];
    }

}
