<?php

namespace App\Lib\Pay;

use Illuminate\Support\Facades\App;
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
        $defaults = config('pay.alipay.default');

        $this->settings = array_merge($defaults, $options);
    }

    /**
     * @return Alipay
     */
    public function getInstance()
    {
        $options = [
            'alipay' => [
                'default' => [
                    // 必填-支付宝分配的 app_id
                    'app_id' => $this->settings['app_id'],
                    // 必填-应用私钥 字符串或路径
                    'app_secret_cert' => 'MIIEpQIBAAKCAQEAlItV7owMaE+zq0ov3ay4DbEmdoQtH3oP19sWED27BVw+0eqjjUFRoQJDRwQ8r1HyYCxb6Mzd3qDdZRht9dwgJs2jMSeZQ/EArVzLFL5uTOTdvLcCUVIMHF5NCYSo9klrJhG8sB2ukpnAxD7nbGBHUhUd531vO8M1RbuEGn8aJQZZFRRiZzk97aKXRhc9My2VgHqXW6j2YpLv9eQ+Rsl7nJjJg/oxGWq+JW+2XGyaGe2Ey3aWh5N/t6b/MU6G+1mAgYOET4RH2Avp8n/0tqYUPDKePZAfbuWdBJHBAhmo2uJwGqC43SmKeYcKFQLW424TNSVMnf9afVeQVPSn4AoeowIDAQABAoIBADbCEoBoch512xa2t2RxSjwJ5NLlsLicx4BcDAsapnm4YpQBeh8VSCEhc2mXf0Nl4wJe99aext9Nz693zPlIFzYWsiTpow8vpX9C6L4R4RlJeRAbKqiNpVdD0ARFOf/oWq5i9Xq6xmLWeQAe7DLAXyo8DJUMYVfXgyjckjsuRZYF6y4uex0lVwrzvKu1VyAk12Obp3qCsv2b1Qsge8cjOrq3b6KZ5RdqlaWuWAvegK1oMy/TTVX6eBncHZrcTL13fA0ytci91SgVXnGPEoxzICNEdtXjCjygaxu5OqjGLzw7HNdDK9UdypbRnFREVuBKCP4/Dqzhi4KFMGcDxWJt7CECgYEA3Ihgq5Xl12qfSJz6liKlDTPV2zQZJPy0J4LS5vacqOS+Ze56gzz8Bex9fXIBDlPARLYuE+RYRukcYpcc+1imF3lZoe9pNEC8dH+fwi4kot6VvKxwXYEQWz/+5m784eg3fZUhb+TwmYDtmDk4cHqYKCrURr9xsWvScykbcphTYnECgYEArG8ZXOZobrMiQMDZDzu5kTTNDblxAjvYtwTLm1NHn3weemiK/SP5ALh8PoIlIyVFTwCwauNSIXAoDJCYGi6CGXSvPbDi/ZKOvvJTF/6AL6yM0439Izf9odroTLWKv0WMtEuIz2zUdUheEjoMuMefN1erj1k57wEhZDU0DCHGdFMCgYEAnjZ07ASBPTcwCN8d46H9OWiLr1RECcYF1SbE+Y/JOl3IvsYFPKv+vp04NkwYt9eb42+zxO+X6V+Jzq1MzIF/vu1/QW7J0gPzb7yzt5J1FCeN0yr+/gX+3wgdbeIIKGX5kW3w7B6aAfZ9/ixm8kLxcDexzQPBpCCPfbDrER55C/ECgYEAjXXzg8BOaoqxDhhnulSQDy0XECxTJrb8OmFHvBydRJyp0FhVtgi97bZrz0gruWKJMp/pGzd0mJQPdwdkkQ4Yk1OjtGOaRNboHoRkYOncNcBEJAZ3Zl43yIHzU4MX2YTwQrU4/ppUzgbZjfBroWe7GcblqwtTON1fjHsNQOqwiZsCgYEAwlblzWHrb8eULGfRc15J5wss11JgqDIJKLxeNLQEByUmxUuBVXSVJGu3SWw+mL9QzN3hXQ6RARLTehIAmBdithUD6YV/4fZKjIWyHYa4NUP5r9ouQtVcgiUXCM7xcZO93LsXZm5I7pzr1rY0zBLMnzve/uT/Yxp1khravCS+yfg=',
                    // 必填-应用公钥证书 路径
                    'app_public_cert_path' => config_path('alipay/appCertPublicKey.crt'),
                    // 必填-支付宝公钥证书 路径
                    'alipay_public_cert_path' => config_path('alipay/alipayCertPublicKey.crt'),
                    // 必填-支付宝根证书 路径
                    'alipay_root_cert_path' => config_path('alipay/alipayRootCert.crt'),
                    'return_url' => $this->settings['return_url'],
                    'notify_url' => $this->settings['notify_url'],
                    // 选填-服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
                    'service_provider_id' => '',
                    // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
                    'mode' => App::environment('prod') ? Pay::MODE_NORMAL : Pay::MODE_SANDBOX,
                ],
            ],
            'logger' => [ // optional
                'enable' => false,
                'file' => './logs/alipay.log',
                'level' => App::environment('prod') ? 'info' : 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ]
        ];
        return Pay::alipay($options);
    }

}
