<?php

namespace App\Lib\Notice\WeChat;

use App\Models\WeChatSubscribe;
use App\Services\WeChatNotice;

class OrderFinish extends WeChatNotice
{

    protected $templateCode = 'order_finish';

    /**
     * @param WeChatSubscribe $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WeChatSubscribe $subscribe, $params)
    {

        $first = '订单已处理完成！';
        $remark = '感谢您的支持，有疑问请联系客服哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => sprintf('%s元', $params['order']['amount']),
            'keyword2' => $params['order']['subject'],
            'keyword3' => date('Y-m-d H:i', $params['order']['update_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
