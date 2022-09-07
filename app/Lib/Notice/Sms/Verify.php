<?php

namespace App\Lib\Notice\Sms;

use App\Services\VerifyService;

class Verify extends Smser
{

    protected $templateCode = 'verify';

    /**
     * @param string $phone
     * @return bool
     */
    public function handle($phone)
    {
        $verify = new VerifyService();

        $minutes = 5;

        $code = $verify->getSmsCode($phone, 60 * $minutes);
        $templateId = $this->getTemplateId($this->templateCode);

        $params = [$code, $minutes];

        return $this->send($phone, $templateId, $params);
    }

}
