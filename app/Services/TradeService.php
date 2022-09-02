<?php

namespace App\Services;

use App\Enums\ClientEnums;
use App\Enums\TradeEnums;
use App\Exceptions\BadRequestException;
use App\Lib\Pay\Wxpay;
use App\Lib\Pay\Alipay;
use App\Services\Token\LoginTokenService;
use App\Traits\ClientTrait;
use App\Traits\OrderTrait;
use App\Traits\TradeTrait;
use App\Utils\CodeResponse;
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
        $trade = Trade::query()->create($tradeData);

        $redirect = '';
        if ($trade->channel == TradeEnums::CHANNEL_ALIPAY) {
            $alipay = new Alipay();
            $response = $alipay->wap($trade);
            $redirect = $response ? $response->getBody()->getContents() : '';
        } elseif ($trade->channel == TradeEnums::CHANNEL_WXPAY) {
            $wxpay = new Wxpay();
            $response = $wxpay->wap($trade);
//            $redirect = $response ? $response->getTargetUrl() : '';
            $redirect = $response ? $response->getBody()->getContents() : '';
        }
        $payment = ['redirect' => $redirect];
        return [
            'trade' => $trade,
            'payment' => $payment,
        ];
    }

    public function createMiniTrade($params)
    {
        $orderSn = $params['order_sn'];
        $channel = $params['channel'];
        $platform = request()->header('x-platform');
        $platform = $this->checkMpPlatform($platform);

        $order = $this->checkOrderBySn($orderSn);
        $this->checkIfAllowPay($order);

        $channel = TradeEnums::CHANNEL_WXPAY;

        if ($platform == ClientEnums::TYPE_MP_ALIPAY) {
            $channel = TradeEnums::CHANNEL_ALIPAY;
        } elseif ($platform == ClientEnums::TYPE_MP_WEIXIN) {
            $channel = TradeEnums::CHANNEL_WXPAY;
        }

        $tradeData = [
            'subject' => $order->subject,
            'amount' => $order->amount,
            'channel' => $channel,
            'order_id' => $order->id,
            'sn' => $order->sn,
            'user_id' => LoginTokenService::userId(),
        ];
        $trade = Trade::query()->create($tradeData);
        $response = null;
        if ($channel == TradeEnums::CHANNEL_ALIPAY) {
            $alipay = new Alipay();
            $buyerId = '';
            $response = $alipay->mini($trade, $buyerId);
        } elseif ($channel == TradeEnums::CHANNEL_WXPAY) {
            $wxpay = new Wxpay();
            $openId = '';
            $response = $wxpay->mini($trade, $openId);
        }

        return $response;
    }


    public function createQrcodeTrade()
    {


        DB::beginTransaction();
        $trade = $this->createHandle();
        $qrCode = $this->getQrCode($trade);

        if ($trade && $qrCode) {

            DB::commit();

            return [
                'sn' => $trade->sn,
                'channel' => $trade->channel,
                'qrcode' => $qrCode,
            ];

        } else {

            DB::commit();

            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '支付失败');
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
        $qrCode = $service->scan($trade);
        return $qrCode;
    }

    protected function getWxpayQrCode(Trade $trade)
    {
        $service = new Wxpay();
        $qrCode = $service->scan($trade);

        return $qrCode;
    }
}
