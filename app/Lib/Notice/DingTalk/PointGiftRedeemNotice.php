<?php


namespace App\Lib\Notice\DingTalk;

use App\Enums\PointGiftEnum;
use App\Enums\TaskEnums;
use App\Models\PointGift;
use App\Models\PointGiftRedeem;
use App\Models\Task;
use App\Repositories\PointGiftRedeemRepository;

class PointGiftRedeemNotice extends DingTalkNotice
{

    public function handleTask(Task $task)
    {
        if (!$this->enabled) return;

        $redeemRepo = new PointGiftRedeemRepository();

        $redeem = $redeemRepo->findById($task->item_id);

        $content = kg_ph_replace("{user.name} 兑换了商品 {gift.name}，不要忘记发货哦！", [
            'user.name' => $redeem->user_name,
            'gift.name' => $redeem->gift_name,
        ]);

        $this->atCustomService($content);
    }

    public function createTask(PointGiftRedeem $redeem)
    {
        if (!$this->enabled) return;

        if ($redeem->gift_type != PointGiftEnum::TYPE_GOODS) return;

        $task = new Task();

        $itemInfo = [
            'point_gift_redeem' => ['id' => $redeem->id],
        ];

        $task->item_id = $redeem->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskEnums::TYPE_STAFF_NOTICE_POINT_GIFT_REDEEM;
        $task->priority = TaskEnums::PRIORITY_MIDDLE;
        $task->status = TaskEnums::STATUS_PENDING;

        $task->save();
    }

}
