<?php

namespace App\Lib\Notice\Sms;

use App\Models\Admin\LinUser as UserModel;
use App\Services\Smser;

class RefundFinish extends Smser
{

    protected $templateCode = 'refund_finish';

    /**
     * @param UserModel $user
     * @param array $params
     * @return bool|null
     */
    public function handle(UserModel $user, array $params)
    {

//        if (!$account->phone) return null;
        //todo 账户
        $phone = 13153187435;
        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['refund']['subject'],
            $params['refund']['sn'],
            $params['refund']['amount'],
        ];

        return $this->send($phone, $templateId, $params);
    }

}
