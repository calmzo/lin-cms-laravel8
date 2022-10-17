<?php

namespace App\Lib\Notice\Sms;

use App\Models\User;
use App\Repositories\AccountRepository;

class LiveBegin extends Smser
{

    protected $templateCode = 'live_begin';

    /**
     * @param User $user
     * @param array $params
     * @return bool|null
     */
    public function handle(User $user, array $params)
    {
        $params['live']['start_time'] = date('H:i', $params['live']['start_time']);

        $accountRepo = new AccountRepository();

        $account = $accountRepo->findById($user->id);

        if (!$account->phone) return null;

        $params = [
            $params['course']['title'],
            $params['chapter']['title'],
            $params['live']['start_time'],
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($account->phone, $templateId, $params);
    }

}
