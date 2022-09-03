<?php

namespace App\Services;

use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;

class WeChat
{

    /**
     * @var mixed|\Psr\Log\LoggerInterface
     */
    public $logger;

    public function __construct()
    {
        $this->logger = Log::channel('wechat');
    }

    public function getOfficialAccount()
    {
        $settings = $this->getSettings('wechat.oa');

        $config = [
            'app_id' => $settings['app_id'],
            'secret' => $settings['app_secret'],
            'token' => $settings['app_token'],
            'aes_key' => $settings['aes_key'],
            'log' => $this->getLogOptions(),
        ];

        return Factory::officialAccount($config);
    }

    protected function getLogOptions()
    {
        $config = $this->getConfig();

        $default = $config->get('env') == ENV_DEV ? 'dev' : 'prod';

        return [
            'default' => $default,
            'channels' => [
                'dev' => [
                    'driver' => 'daily',
                    'path' => log_path('wechat.log'),
                    'level' => 'debug',
                ],
                'prod' => [
                    'driver' => 'daily',
                    'path' => log_path('wechat.log'),
                    'level' => 'info',
                ],
            ]
        ];
    }

}
