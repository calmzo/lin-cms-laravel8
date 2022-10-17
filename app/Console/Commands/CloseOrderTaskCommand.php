<?php

namespace App\Console\Commands;

use App\Enums\OrderEnums;
use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Carbon;

class CloseOrderTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close_order';

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
        $orders = $this->findOrders();

        echo sprintf('pending orders: %s', $orders->count()) . PHP_EOL;

        if ($orders->count() == 0) return;

        echo '------ start close order task ------' . PHP_EOL;

        foreach ($orders as $order) {
            $order->status = OrderEnums::STATUS_CLOSED;
            $order->update();
        }
        echo '------ end close order task ------' . PHP_EOL;
    }


    /**
     * 查找待关闭订单
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findOrders($limit = 1000)
    {
        $status = OrderEnums::STATUS_PENDING;
        $time = Carbon::now()->subDays(12);
        $type = 0;
        return Order::query()
            ->where('status', $status)
            ->where('promotion_type', $type)
            ->where('create_time', '<', $time)
            ->limit($limit)
            ->get();
    }
}
