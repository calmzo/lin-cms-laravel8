<?php

namespace App\Services\Logic\Refund;

use App\Services\Logic\LogicService;
use App\Services\RefundService;
use App\Traits\OrderTrait;

class RefundConfirmService extends LogicService
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $service = new RefundService();

        return $service->preview($order);
    }

}
