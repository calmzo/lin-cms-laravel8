<?php

namespace App\Caches;

use App\Services\StatService;

class SiteTodayStatCache extends Cache
{

    protected $lifetime = 15 * 60;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'site_today_stat';
    }

    public function getContent($id = null)
    {
        $statService = new StatService();

        $date = date('Y-m-d');

        $saleCount = $statService->countDailySales($date);
        $refundCount = $statService->countDailyRefunds($date);
        $saleAmount = $statService->sumDailySales($date);
        $refundAmount = $statService->sumDailyRefunds($date);
        $registerCount = $statService->countDailyRegisteredUsers($date);
        $pointRedeemCount = $statService->countDailyPointGiftRedeems($date);

        return [
            'sale_count' => $saleCount,
            'refund_count' => $refundCount,
            'sale_amount' => $saleAmount,
            'refund_amount' => $refundAmount,
            'register_count' => $registerCount,
            'point_redeem_count' => $pointRedeemCount,
        ];
    }

}
