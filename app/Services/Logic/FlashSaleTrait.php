<?php

namespace App\Services\Logic;

use App\Validators\FlashSaleValidator;

trait FlashSaleTrait
{

    public function checkFlashSale($id)
    {
        $validator = new FlashSaleValidator();

        return $validator->checkFlashSale($id);
    }

    public function checkFlashSaleCache($id)
    {
        $validator = new FlashSaleValidator();

        return $validator->checkFlashSaleCache($id);
    }

}
