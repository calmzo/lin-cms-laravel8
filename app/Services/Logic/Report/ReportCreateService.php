<?php

namespace App\Services\Logic\Report;

use App\Events\IncrReportCountEvent;
use App\Events\ReportAfterCreateEvent;
use App\Models\Report;
use App\Models\User;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ClientTrait;
use App\Traits\UserLimitTrait;
use App\Traits\ReportCountTrait;
use App\Lib\Validators\ReportValidator;

class ReportCreateService
{

    use ClientTrait;
    use ReportCountTrait;
    use UserLimitTrait;

    public function handle($params)
    {

        $itemId = $params['item_id'];
        $itemType = $params['item_type'];
        $reason = $params['reason'];
        $remark = $params['remark'];

        $user = AccountLoginTokenService::user();

        $this->checkDailyReportLimit($user);

        $validator = new ReportValidator();

        $item = $validator->checkItem($itemId, $itemType);
        $validator->checkIfReported($user->id, $itemId, $itemType);

        $report = new Report();

        $report->reason = $validator->checkReason($reason, $remark);
        $report->client_type = $this->getClientType();
        $report->client_ip = $this->getClientIp();
        $report->item_type = $itemType;
        $report->item_id = $itemId;
        $report->user_id = $user->id;

        $report->save();

        $this->incrUserDailyReportCount($user);

        $this->handleItemReportCount($item);
        ReportAfterCreateEvent::dispatch($report);
    }

    protected function incrUserDailyReportCount(User $user)
    {
        IncrReportCountEvent::dispatch($user);
    }

}
