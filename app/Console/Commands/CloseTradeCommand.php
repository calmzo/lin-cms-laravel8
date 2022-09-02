<?php

namespace App\Console\Commands;

use App\Enums\TradeEnums;
use App\Events\TradeAfterPayEvent;
use App\Lib\Pay\Alipay;
use App\Lib\Pay\Wxpay;
use App\Models\Trade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloseTradeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close_trade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $trades = $this->findTrades();
        Log::channel('trade')->info(sprintf('pending trades: %s', $trades->count()) . PHP_EOL);
        if ($trades->count() == 0) return;
        Log::channel('trade')->info('------ start close trade ------' . PHP_EOL);

        foreach ($trades as $trade) {
            if ($trade->channel == TradeEnums::CHANNEL_ALIPAY) {
                $this->handleAlipayTrade($trade);
            } elseif ($trade->channel == TradeEnums::CHANNEL_WXPAY) {
                $this->handleWxpayTrade($trade);
            }
        }
        Log::channel('trade')->info('------ end close trade ------' . PHP_EOL);

    }

    /**
     * 处理支付宝交易
     *
     * @param Trade $trade
     */
    protected function handleAlipayTrade(Trade $trade)
    {
        $allowClosed = true;

        $alipay = new Alipay();

        $alipayTrade = $alipay->find($trade->sn);

        if ($alipayTrade) {

            /**
             * 异步通知接收异常，补救漏网
             */
            if ($alipayTrade->trade_status == 'TRADE_SUCCESS') {

                TradeAfterPayEvent::dispatch($trade);

                $allowClosed = false;

            } elseif ($alipayTrade->trade_status == 'WAIT_BUYER_PAY') {

                $allowClosed = $alipay->close($trade->sn);
            }
        }

        if (!$allowClosed) return;

        $trade->status = TradeEnums::STATUS_CLOSED;

        $trade->save();
    }

    /**
     * 处理微信交易
     *
     * @param Trade $trade
     */
    protected function handleWxpayTrade(Trade $trade)
    {
        $allowClosed = true;

        $wxpay = new Wxpay();

        $wxpayTrade = $wxpay->find($trade->sn);

        if ($wxpayTrade) {

            /**
             * 异步通知接收异常，补救漏网
             */
            if ($wxpayTrade->trade_state == 'SUCCESS') {

                TradeAfterPayEvent::dispatch($trade);

                $allowClosed = false;

            } elseif ($wxpayTrade->trade_state == 'NOTPAY') {

                $allowClosed = $wxpay->close($trade->sn);
            }
        }

        if (!$allowClosed) return;

        $trade->status = TradeEnums::STATUS_CLOSED;

        $trade->save();
    }

    /**
     * 查找待关闭交易
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findTrades($limit = 50)
    {
        $status = TradeEnums::STATUS_PENDING;

        $createTime = date('Y-m-d H:i:s', time() - 15 * 60);

        return Trade::query()
            ->where('status', $status)
            ->where('create_time', '<', $createTime)
            ->limit($limit)
            ->get();
    }
}
