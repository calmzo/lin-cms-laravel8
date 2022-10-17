<?php

namespace App\Lib\Notice\WeChat;

use App\Models\WeChatSubscribe;
use App\Services\WeChatNotice;

class LiveBegin extends WeChatNotice
{

    protected $templateCode = 'live_begin';

    /**
     * @param WeChatSubscribe $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WeChatSubscribe $subscribe, array $params)
    {
        $first = '你参与的课程直播就要开始了！';

        $startTime = date('H:i', $params['live']['start_time']);

        $remark = sprintf('课程直播：%s 即将在 %s 开始, 千万不要错过哦！', $params['chapter']['title'], $startTime);

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => $params['course']['title'],
            'keyword2' => $params['user']['name'],
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
