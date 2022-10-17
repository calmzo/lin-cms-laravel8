<?php

namespace App\Lib\Notice\Sms;

use App\Models\User;
use App\Repositories\AccountRepository;

class GoodsDeliver extends Smser
{

    protected $templateCode = 'goods_deliver';

    /**
     * @param User $user
     * @param array $params
     * @return bool|null
     */
    public function handle(User $user, array $params)
    {
        $params['deliver_time'] = date('Y-m-d H:i', $params['deliver_time']);

        $accountRepo = new AccountRepository();

        $account = $accountRepo->findById($user->id);

        if (!$account->phone) return null;

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['goods_name'],
            $params['order_sn'],
            $params['deliver_time'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
