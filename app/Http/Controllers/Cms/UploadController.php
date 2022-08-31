<?php

namespace App\Http\Controllers\Cms;

use App\Lib\VodService;

class UploadController extends BaseController
{

    public $only = [];

    /**
     * 获取上传签名
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getVoidSign()
    {
        $service = new VodService();
        $sign = $service->getUploadSignature();
        $data = ['sign' => $sign];
        return $this->success($data);

    }
}
