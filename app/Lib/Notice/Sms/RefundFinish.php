<?php

namespace App\Lib\Notice\Sms;

use App\Models\Account;
use App\Models\User;

class RefundFinish extends Smser
{

    protected $templateCode = 'refund_finish';

    /**
     * @param User $user
     * @param array $params
     * @return bool|null
     */
    public function handle(User $user, array $params)
    {

        $account = Account::query()->where('user_id', $user->id)->first();
        if (!$account->phone) return null;
        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['refund']['subject'],
            $params['refund']['sn'],
            $params['refund']['amount'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
