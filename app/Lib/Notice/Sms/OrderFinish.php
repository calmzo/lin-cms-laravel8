<?php

namespace App\Lib\Notice\Sms;

use App\Models\User;
use App\Services\Smser;

class OrderFinish extends Smser
{

    protected $templateCode = 'order_finish';

    /**
     * @param User $user
     * @param array $params
     * @return bool|null
     */
    public function handle(User $user, array $params)
    {
//        //todo 账户表
//        $accountRepo = new AccountRepo();
//        $account = $accountRepo->findById($user->id);
//        if (!$account->phone) return null;
        $phone = 13153187435;
        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['order']['subject'],
            $params['order']['sn'],
            $params['order']['amount'],
        ];

        return $this->send($phone, $templateId, $params);
    }

}
