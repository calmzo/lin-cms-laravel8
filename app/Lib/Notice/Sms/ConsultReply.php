<?php

namespace App\Lib\Notice\Sms;

use App\Models\Account;
use App\Models\User;

class ConsultReply extends Smser
{

    protected $templateCode = 'consult_reply';

    /**
     * @param User $user
     * @param array $params
     * @return bool|null
     */
    public function handle(User $user, array $params)
    {

        $account = Account::query()->find($user->id);
        if (!$account->phone) return null;

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['replier']['name'],
            $params['course']['title'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
