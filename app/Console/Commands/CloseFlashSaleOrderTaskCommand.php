<?php

namespace App\Console\Commands;

use App\Enums\OrderEnums;
use App\Repositories\FlashSaleRepository;
use App\Services\Logic\FlashSale\Queue;
use App\Services\Logic\FlashSale\UserOrderCache;
use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Carbon;

class CloseFlashSaleOrderTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close_flash_sale_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '限时秒杀交易关闭任务';

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
        $orders = $this->findOrders();

        echo sprintf('pending orders: %s', $orders->count()) . PHP_EOL;

        if ($orders->count() == 0) return;

        echo '------ start close order task ------' . PHP_EOL;

        foreach ($orders as $order) {
            $this->incrFlashSaleStock($order->promotion_id);
            $this->pushFlashSaleQueue($order->promotion_id);
            $this->deleteUserOrderCache($order->user_id, $order->promotion_id);
            $order->status = OrderEnums::STATUS_CLOSED;
            $order->update();
        }

        echo '------ end close order task ------' . PHP_EOL;
    }

    protected function incrFlashSaleStock($saleId)
    {
        $flashSaleRepo = new FlashSaleRepository();

        $flashSale = $flashSaleRepo->findById($saleId);

        $flashSale->stock += 1;

        $flashSale->update();
    }

    protected function pushFlashSaleQueue($saleId)
    {
        $queue = new Queue();

        $queue->push($saleId);
    }

    protected function deleteUserOrderCache($userId, $saleId)
    {
        $cache = new UserOrderCache();

        $cache->delete($userId, $saleId);
    }

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findOrders($limit = 1000)
    {
        $status = OrderEnums::STATUS_PENDING;
        $type = OrderEnums::PROMOTION_FLASH_SALE;
        $time = Carbon::now()->subMinutes(15);

        return Order::query()
            ->where('status', $status)
            ->where('promotion_type', $type)
            ->where('create_time', '<', $time)
            ->limit($limit)
            ->get();
    }

}
