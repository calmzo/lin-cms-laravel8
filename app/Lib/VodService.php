<?php

namespace App\Lib;

class VodService
{


    /**
     * 获取上传签名
     *
     * @return string
     */
    public function getUploadSignature()
    {
        $config = Config('vod');
        $secretId = $config['secret_id'] ?? '';
        $secretKey = $config['secret_key'] ?? '';

        $params = [
            'secretId' => $secretId,
            'currentTimeStamp' => time(),
            'expireTime' => time() + 86400,
            'random' => rand(1000, 9999),
        ];

        $original = http_build_query($params);

        $hash = hash_hmac('SHA1', $original, $secretKey, true);

        return base64_encode($hash . $original);
    }

}
