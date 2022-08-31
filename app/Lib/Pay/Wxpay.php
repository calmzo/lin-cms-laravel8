<?php

namespace App\Lib\Pay;

use App\Enums\TradeEnums;
use App\Models\Refund;
use App\Models\Trade;
use App\Lib\Pay\Pay as PayService;
use Yansongda\Pay\Logger;
use Yansongda\Supports\Collection;
use Yansongda\Pay\Pay;

class Wxpay extends PayService
{


    /**
     * 扫码下单
     *
     * @param Trade $trade
     * @return bool|string
     */
    public function scan(Trade $trade)
    {
        try {

            $response = Pay::alipay()->scan([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);

            $result = $response->code_url ?? false;

        } catch (\Exception $e) {

            Logger::error('Wxpay Scan Error', [
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
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);

        } catch (\Exception $e) {

            Logger::error('Wxpay App Exception', [
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
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);

        } catch (\Exception $e) {

            Logger::error('Wxpay Wap Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 公众号支付
     *
     * @param Trade $trade
     * @param string $openId
     * @return Collection|bool
     */
    public function mp(Trade $trade, $openId)
    {
        try {

            $result = Pay::alipay()->mp([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
                'openid' => $openId,
            ]);

        } catch (\Exception $e) {

            Logger::error('Wxpay MP Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 小程序支付
     *
     * @param Trade $trade
     * @param string $openId
     * @return Collection|bool
     */
    public function mini(Trade $trade, $openId)
    {
        try {

            $result = Pay::alipay()->miniapp([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
                'openid' => $openId,
            ]);

        } catch (\Exception $e) {

            Logger::error('Wxpay Mini Exception', [
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

            Logger::debug('Wxpay Verify Data', $data->all());

        } catch (\Exception $e) {

            Logger::error('Wxpay Verify Error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        if ($data->result_code != 'SUCCESS') {
            return false;
        }

        $trade = Trade::query()->where('sn', $data->out_trade_no)->first();

        if (!$trade) return false;

        /**
         * 注意浮点数精度丢失问题
         */
        $amount = intval(strval(100 * $trade->amount));

        if ($data->total_fee != $amount) {
            return false;
        }

        if ($trade->status == TradeEnums::STATUS_FINISHED) {
            return Pay::alipay()->success();
        }

        if ($trade->status != TradeEnums::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $data->transaction_id;

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

            $result = $response->result_code == 'SUCCESS';

        } catch (\Exception $e) {

            Logger::error('Wxpay Close Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 取消交易
     *
     * @param string $tradeNo
     * @return bool
     */
    public function cancel($tradeNo)
    {
        return $this->close($tradeNo);
    }

    /**
     * 申请退款
     *
     * @param Refund $refund
     * @return Collection|bool
     */
    public function refund(Refund $refund)
    {
        try {

            $trade = Trade::query()->where('sn', $refund->trade_id)->first();

            $response = Pay::alipay()->refund([
                'out_trade_no' => $trade->sn,
                'out_refund_no' => $refund->sn,
                'total_fee' => 100 * $trade->amount,
                'refund_fee' => 100 * $refund->amount,
            ]);

            $result = $response->result_code == 'SUCCESS';

        } catch (\Exception $e) {

            Logger::error('Wxpay Refund Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

}
