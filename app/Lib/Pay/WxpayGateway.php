<?php

namespace App\Lib\Pay;

use Yansongda\Pay\Gateways\Wechat;
use Yansongda\Pay\Pay;

class WxpayGateway
{

    /**
     * @var array
     */
    protected $settings;

    public function __construct($options = [])
    {
        $defaults = config('pay');

        $this->settings = array_merge($defaults, $options);
    }

    public function setReturnUrl($returnUrl)
    {
        $this->settings['return_url'] = $returnUrl;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->settings['notify_url'] = $notifyUrl;
    }

    /**
     * @return \Yansongda\Pay\Gateways\Wechat|\Yansongda\Pay\Provider\Wechat
     */
    public function getInstance()
    {
        return Pay::wechat($this->settings);

    }

}
