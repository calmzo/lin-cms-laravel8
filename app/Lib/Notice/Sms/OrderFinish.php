<?php

namespace App\Lib\Notice\Sms;

use App\Models\Account;
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
        $account = Account::query()->find($user->id);
        if (!$account->phone) {
            return null;
        }
        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['order']['subject'],
            $params['order']['sn'],
            $params['order']['amount'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
