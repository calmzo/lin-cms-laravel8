<?php

namespace App\Lib\Notice\Sms;

use Illuminate\Support\Facades\Log;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Sms\V20210111\Models\SendStatus;
use TencentCloud\Sms\V20210111\SmsClient;

abstract class Smser
{

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var mixed|\Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        $this->settings = config('sms');
        $this->logger = Log::channel('sms');
    }

    /**
     * 发送短信
     *
     * @param string $phoneNumber
     * @param string $templateId
     * @param array $params
     * @return bool
     */
    public function send($phoneNumber, $templateId, $params)
    {
        $secret = config('captcha.secret');

        $region = $this->settings['region'] ?: 'ap-guangzhou';

        $templateParams = $this->formatTemplateParams($params);

        try {
            $credential = new Credential($secret['secret_id'], $secret['secret_key']);
            $httpProfile = new HttpProfile();

            $httpProfile->setEndpoint($this->settings['endpoint']);

            $clientProfile = new ClientProfile();

            $clientProfile->setHttpProfile($httpProfile);

            $client = new SmsClient($credential, $region, $clientProfile);

            $request = new SendSmsRequest();

            $params = json_encode([
                'SmsSdkAppId' => $this->settings['app_id'],
                'SignName' => $this->settings['signature'],
                'TemplateId' => $templateId,
                'TemplateParamSet' => $templateParams,
                'PhoneNumberSet' => [$phoneNumber],
            ]);

            $request->fromJsonString($params);

            $this->logger->debug('Send Message Request ' . $params);

            $response = $client->SendSms($request);

            $this->logger->debug('Send Message Response ' . $response->toJsonString());

            /**
             * @var $sendStatus SendStatus
             */
            $sendStatus = $response->getSendStatusSet()[0];

            $result = $sendStatus->getCode() == 'Ok';

            if ($result === false) {
                $this->logger->error('Send Message Failed ' . $response->toJsonString());
            }

        } catch (TencentCloudSDKException $e) {
            $this->logger->error('Send Message Exception ' . json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));
            $result = false;
        }

        return $result;
    }

    protected function formatTemplateParams($params)
    {
        if (!empty($params)) {
            $params = array_map(function ($value) {
                return strval($value);
            }, $params);
        }

        return $params;
    }

    protected function getTemplateId($code)
    {
        $template = $this->settings['template'];

        return $template[$code]['id'] ?? null;
    }

}
