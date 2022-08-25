<?php

namespace App\Http\Controllers\Cms;

use App\Exceptions\File\FileException;
use App\Lib\UploadService;
use App\Models\Admin\LinFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
 * @package app\api\controller\cms
 */
class FileController extends BaseController
{
    protected $only = [];

    /**
     * @return mixed
     * @throws FileException
     * @throws \LinCmsTp\exception\FileException
     */
    public function postFile(Request $request)
    {
        if(!$request->hasFile('file'))
        {
            throw new FileException();
        }
        return UploadService::upload($request->file('file'));
    }
}
