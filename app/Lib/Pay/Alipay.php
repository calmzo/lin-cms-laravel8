<?php

namespace App\Lib\Pay;

use App\Enums\TradeEnums;
use App\Lib\Pay\Pay as PayService;
use App\Models\Refund;
use Yansongda\Pay\Logger;
use App\Models\Trade;
use Yansongda\Supports\Collection;
use Yansongda\Pay\Pay;

class Alipay extends PayService
{

    /**
     * 扫码下单
     * @param Trade $trade
     * @return false|mixed
     */
    public function scan(Trade $trade)
    {
        try {

            $response = Pay::alipay()->scan([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
            ]);

            $result = $response->qr_code ?? false;

        } catch (\Exception $e) {

            Logger::error('Alipay Qrcode Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * app支付
     * @param Trade $trade
     * @return false|\Psr\Http\Message\ResponseInterface|\Symfony\Component\HttpFoundation\Response
     */
    public function app(Trade $trade)
    {
        try {

            $result = Pay::alipay()->app([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
            ]);

        } catch (\Exception $e) {

            Logger::error('Alipay app Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * wap支付
     * @param Trade $trade
     * @return false|\Psr\Http\Message\ResponseInterface|\Symfony\Component\HttpFoundation\Response
     */
    public function wap(Trade $trade)
    {
        try {

            $result = Pay::alipay()->wap([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
                'http_method' => 'GET',
            ]);

        } catch (\Exception $e) {

            Logger::error('Alipay Wap Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 小程序支付
     * @param Trade $trade
     * @param $buyerId
     * @return false|Collection
     */
    public function mini(Trade $trade, $buyerId)
    {
        try {

            $result = Pay::alipay()->mini([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
                'buyer_id' => $buyerId,
            ]);

        } catch (\Exception $e) {

            Logger::error('Alipay Mini Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 异步通知
     * @return false|\Psr\Http\Message\ResponseInterface|\Symfony\Component\HttpFoundation\Response
     */
    public function notify()
    {
        try {

            $data = Pay::alipay()->verify();

            Logger::debug('Alipay Verify Data', $data->all());

        } catch (\Exception $e) {

            Logger::error('Alipay Verify Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        if ($data->trade_status != 'TRADE_SUCCESS') {
            return false;
        }

        $trade = Trade::query()->where('sn', $data->out_trade_no)->first();

        if (!$trade) return false;

        if ($data->total_amount != $trade->amount) {
            return false;
        }

        if ($trade->status == TradeEnums::STATUS_FINISHED) {
            return Pay::alipay()->success();
        }

        if ($trade->status != TradeEnums::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $data->trade_no;

//        $this->eventsManager->fire('Trade:afterPay', $this, $trade);
        $trade = Trade::query()->where('id', $trade->id)->first();

        if ($trade->status == TradeEnums::STATUS_FINISHED) {
            return Pay::alipay()->success();
        }

        return false;
    }

    /**
     * 查询交易（扫码生成订单后可执行）
     *
     * @param string $tradeNo
     * @param string $type
     * @return Collection|bool
     */
    public function find($tradeNo, $type = 'wap')
    {
        try {

            $order = ['out_trade_no' => $tradeNo];

            $result = Pay::alipay()->find($order);

        } catch (\Exception $e) {

            Logger::error('Alipay Find Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 关闭交易（扫码生成订单后可执行）
     *
     * @param string $tradeNo
     * @return bool
     */
    public function close($tradeNo)
    {
        try {

            $response = Pay::alipay()->close(['out_trade_no' => $tradeNo]);

            $result = $response->code == '10000';

        } catch (\Exception $e) {

            Logger::error('Alipay Close Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 撤销交易（未生成订单也可执行）
     *
     * @param string $tradeNo
     * @return bool
     */
    public function cancel($tradeNo)
    {
        try {

            $response = Pay::alipay()->cancel(['out_trade_no' => $tradeNo]);

            $result = $response->code == '10000';

        } catch (\Exception $e) {

            Logger::error('Alipay Cancel Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 申请退款
     *
     * @param Refund $refund
     * @return bool
     */
    public function refund(Refund $refund)
    {
        try {

            $trade = Trade::query()->where('sn', $refund->trade_id)->first();

            $response = Pay::alipay()->refund([
                'out_trade_no' => $trade->sn,
                'out_request_no' => $refund->sn,
                'refund_amount' => $refund->amount,
            ]);

            $result = $response->code == '10000';

        } catch (\Exception $e) {

            Logger::error('Alipay Refund Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

}
