<?php

namespace App\Services\Logic\User\Console;

use App\Models\User;
use App\Models\Online;
use App\Repositories\OnlineRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\Point\History\SiteVisitPointHistory;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ClientTrait;
use Illuminate\Support\Facades\Cache;

class ConsoleOnlineService extends LogicService
{

    use ClientTrait;

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $this->handleVisitLog($user);

        $this->handleVisitPoint($user);
    }

    protected function handleVisitLog(User $user)
    {
        $now = time();

        if ($now - $user->active_time < 900) return;

        $user->active_time = $now;

        $user->save();

        $onlineRepo = new OnlineRepository();

        $records = $onlineRepo->findByUserDate($user->id, date('Ymd'));

        $clientType = $this->getClientType();
        $clientIp = $this->getClientIp();

        if ($records->count() > 0) {
            $online = null;
            foreach ($records as $record) {
                $case1 = $record->client_type == $clientType;
                $case2 = $record->client_ip == $clientIp;
                if ($case1 && $case2) {
                    $online = $record;
                    break;
                }
            }
            if ($online) {
                $online->active_time = $now;
                $online->save();
            } else {
                $this->createOnline($user->id, $clientType, $clientIp);
            }
        } else {
            $this->createOnline($user->id, $clientType, $clientIp);
        }
    }

    protected function createOnline($userId, $clientType, $clientIp)
    {
        $data = [
            'user_id' => $userId,
            'client_type' => $clientType,
            'client_ip' => $clientIp,
            'active_time' => time()
        ];
        return Online::query()->create($data);
    }

    protected function handleVisitPoint(User $user)
    {
        $todayDate = date('Ymd');

        $keyName = sprintf('site_visit:%s:%s', $user->id, $todayDate);

        if (Cache::has($keyName)) return;
        /**
         * 先写入缓存，再处理访问积分，防止重复插入记录
         */
        Cache::put($keyName, 1, 86400);

        $service = new SiteVisitPointHistory();

        $service->handle($user);
    }

}
