<?php

namespace App\Lib\Pay;

use Yansongda\Pay\Gateways\Alipay;
use Yansongda\Pay\Pay;

class AlipayGateway
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

    /**
     * @return Alipay|\Yansongda\Pay\Provider\Alipay
     */
    public function getInstance()
    {
        return Pay::alipay($this->settings);
    }

}
