<?php

namespace App\Services;

use App\Enums\TradeEnums;
use App\Lib\Pay\Wxpay;
use App\Lib\Pay\Alipay;
use App\Services\Token\LoginTokenService;
use App\Traits\ClientTrait;
use App\Traits\OrderTrait;
use App\Traits\TradeTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Trade;

class TradeService
{
    use OrderTrait, TradeTrait, ClientTrait;


    public function createH5Trade($params)
    {
        $orderSn = $params['order_sn'];
        $channel = $params['channel'];
        $platform = request()->header('x-platform');
        $this->checkH5Platform($platform);
        $order = $this->checkOrderBySn($orderSn);
        $this->checkIfAllowPay($order);
        $channel = $this->checkChannel($channel);
        $tradeData = [
            'subject' => $order->subject,
            'amount' => $order->amount,
            'channel' => $channel,
            'order_id' => $order->id,
            'sn' => $order->sn,
            'user_id' => LoginTokenService::userId(),
        ];
//        $trade = Trade::query()->create($tradeData);
        $trade = Trade::query()->first();

        $redirect = '';
        if ($trade->channel == TradeEnums::CHANNEL_ALIPAY) {
            $alipay = new Alipay();
            $response = $alipay->wap($trade);
            $redirect = $response ? $response->getTargetUrl() : '';
        } elseif ($trade->channel == TradeEnums::CHANNEL_WXPAY) {
            $wxpay = new Wxpay();
            $response = $wxpay->wap($trade);
            $redirect = $response ? $response->getTargetUrl() : '';
        }
        $payment = ['redirect' => $redirect];
        return [
            'trade' => $trade,
            'payment' => $payment,
        ];
    }


    public function create($params)
    {


        DB::beginTransaction();
        $service = new TradeCreateService();

        $trade = $service->handle();

        $qrCode = $this->getQrCode($trade);

        if ($trade && $qrCode) {

            $this->db->commit();

            return [
                'sn' => $trade->sn,
                'channel' => $trade->channel,
                'qrcode' => $qrCode,
            ];

        } else {

            DB::rollBack();

            throw new BadRequestException('trade.create_failed');
        }
    }

    protected function getQrCode(Trade $trade)
    {
        $qrCode = null;

        if ($trade->channel == TradeEnums::CHANNEL_ALIPAY) {
            $qrCode = $this->getAlipayQrCode($trade);
        } elseif ($trade->channel == TradeEnums::CHANNEL_WXPAY) {
            $qrCode = $this->getWxpayQrCode($trade);
        }

        return $qrCode;
    }

    protected function getAlipayQrCode(Trade $trade)
    {
        $service = new Alipay();
        $text = $service->scan($trade);

        return $qrCode;
    }

    protected function getWxpayQrCode(Trade $trade)
    {
        $service = new Wxpay();
        $qrCode = $service->scan($trade);

        return $qrCode;
    }
}
