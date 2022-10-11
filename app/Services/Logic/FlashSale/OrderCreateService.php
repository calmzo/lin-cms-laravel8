<?php

namespace App\Services\Logic\FlashSale;

use App\Enums\FlashSaleEnum;
use App\Enums\OrderEnums;
use App\Exceptions\BadRequestException;
use App\Models\FlashSale;
use App\Models\Order;
use App\Services\Logic\FlashSaleTrait;
use App\Services\OrderService;
use App\Services\Token\AccountLoginTokenService;
use App\Utils\CodeResponse;
use App\Validators\FlashSaleValidator;
use App\Validators\OrderValidator;

class OrderCreateService extends OrderService
{

    use FlashSaleTrait;

    public function handle($params)
    {
        $id = $params['id'] ?? 0;

        $user = AccountLoginTokenService::userModel();

        $sale = $this->checkFlashSale($id);

        $saleValidator = new FlashSaleValidator();

        $saleValidator->checkIfExpired($sale->end_time);
        $saleValidator->checkIfOutSchedules($sale->schedules);
        $saleValidator->checkIfNotPaid($user->id, $sale->id);

        $queue = new Queue();

        if ($queue->pop($id) === false) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'flash_sale.out_stock');
        }

        $this->amount = $sale->price;
        $this->promotion_id = $sale->id;
        $this->promotion_type = OrderEnums::PROMOTION_FLASH_SALE;
        $this->promotion_info = [
            'flash_sale' => [
                'id' => $sale->id,
                'price' => $sale->price,
            ]
        ];

        $orderValidator = new OrderValidator();

        $orderValidator->checkAmount($this->amount);

        try {

            $order = new Order();

            if ($sale->item_type == FlashSaleEnum::ITEM_COURSE) {

                $course = $orderValidator->checkCourse($sale->item_id);

                $orderValidator->checkIfBoughtCourse($user->id, $course->id);

                $order = $this->createCourseOrder($course, $user);

            } elseif ($sale->item_type == FlashSaleEnum::ITEM_PACKAGE) {

                $package = $orderValidator->checkPackage($sale->item_id);

                $orderValidator->checkIfBoughtPackage($user->id, $package->id);

                $order = $this->createPackageOrder($package, $user);

            } elseif ($sale->item_type == FlashSaleEnum::ITEM_VIP) {

                $vip = $orderValidator->checkVip($sale->item_id);

                $order = $this->createVipOrder($vip, $user);
            }

            $this->decrFlashSaleStock($sale);

            $this->saveUserOrderCache($user->id, $sale->id);

            return $order;

        } catch (\Exception $e) {

            $queue->push($sale->id);

            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, $e->getMessage());
        }
    }

    protected function decrFlashSaleStock(FlashSale $sale)
    {
        if ($sale->stock < 1) return;

        if ($sale->stock == 1) $sale->published = 0;

        $sale->stock -= 1;

        $sale->save();
    }

    protected function saveUserOrderCache($userId, $saleId)
    {
        $cache = new UserOrderCache();

        return $cache->save($userId, $saleId);
    }

}
