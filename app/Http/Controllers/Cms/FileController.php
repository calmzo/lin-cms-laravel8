<?php

namespace App\Http\Controllers\Cms;

use think\facade\Request;
use app\lib\file\LocalUploader;
use app\lib\exception\file\FileException;

/**
 * Class File
 * @package app\api\controller\cms
 */
class FileController
{
    protected $only = [];

    /**
     * @return mixed
     * @throws FileException
     * @throws \LinCmsTp\exception\FileException
     */
    public function postFile()
    {
        try {
            $request = Request::file();
        } catch (\Exception $e) {
            throw new FileException([
                'msg' => '字段中含有非法字符',
            ]);
        }
        $file = (new LocalUploader($request))->upload();
        return $file;
    }
}
