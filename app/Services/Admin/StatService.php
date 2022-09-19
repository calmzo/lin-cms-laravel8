<?php

namespace App\Services\Admin;


use App\Caches\Cache as AppInfoCache;
use App\Caches\SiteGlobalStatCache;
use App\Caches\Cache as SiteTodayStatCache;
use App\Http\Admin\Services\AuthMenu;
use App\Library\AppInfo;
use App\Library\Utils\ServerInfo;
use App\Models\OrderStatus as OrderStatusModel;
use App\Repos\Stat as StatRepo;

class StatService
{

    public function countDailySales($date)
    {
        $sql = "SELECT count(*) AS total_count FROM %s AS os JOIN %s AS o ON os.order_id = o.id ";

        $sql .= "WHERE os.status = ?1 AND o.create_time BETWEEN ?2 AND ?3";

        $phql = sprintf($sql, OrderStatusModel::class, OrderModel::class);

        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        $result = $this->modelsManager->executeQuery($phql, [
            1 => OrderModel::STATUS_FINISHED,
            2 => $startTime,
            3 => $endTime,
        ]);

        return (float)$result[0]['total_count'];
    }
}
