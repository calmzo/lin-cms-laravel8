<?php

namespace App\Services;

use App\Services\Logic\FlashSale\OrderCreateService;
use App\Services\Logic\FlashSale\SaleListService;

class FlashSaleService extends BaseService
{

    public function getFlashSales()
    {
        $service = new SaleListService();

        $sales = $service->handle();
        return ['sales' => $sales];
    }

    public function createOrder($params)
    {
        $service = new OrderCreateService();

        $order = $service->handle($params);

        $service = new OrderInfoService();

        $order = $service->handle($order->sn);
        return ['order' => $order];
    }

}
