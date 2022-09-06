<?php

namespace App\Lib\Notice\WeChat;

use App\Services\WeChat as WeChatService;
use Illuminate\Support\Facades\Log;

abstract class WeChatNotice
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
        $this->settings = config('wechat.oa');

        $this->logger = Log::channel('wechat');
    }

    /**
     * 发送模板消息
     *
     * @param string $openId
     * @param string $templateId
     * @param array $params
     * @param string $url
     * @param array $miniProgram
     * @return bool
     */
    public function send($openId, $templateId, $params, $url = null, $miniProgram = [])
    {
        $service = new WeChatService();

        $app = $service->getOfficialAccount();

        $content = [
            'touser' => $openId,
            'template_id' => $templateId,
            'data' => $this->formatParams($params),
        ];

        if ($url) {
            $content['url'] = $url;
        }

        if ($miniProgram) {
            $content['miniprogram'] = $miniProgram;
        }

        try {

            $this->logger->debug('Send Template Message Request ' . json_encode($content, JSON_UNESCAPED_SLASHES));

            $response = $app->template_message->send($content);

            $this->logger->debug('Send Template Message Response ' . json_encode($response, JSON_UNESCAPED_SLASHES));

            $result = $response['errcode'] == 0;

            if ($result == false) {
                $this->logger->error('Send Template Message Failed ' . json_encode($response, JSON_UNESCAPED_SLASHES));
            }

        } catch (\Exception $e) {

            $this->logger->error('Send Template Message Exception ' . json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function formatParams($params)
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
        $template = json_decode($this->settings['notice_template'], true);

        return $template[$code]['id'] ?? null;
    }

}
