<?php

namespace App\Http\Controllers\Cms;

use App\Exceptions\File\FileException;
use App\Lib\UploadService;
use Illuminate\Http\Request;

/**
 *
 */
class FileController extends BaseController
{
    protected $only = [];

    /**
     *
     * @param Request $request
     * @return array
     * @throws FileException
     */
    public function postFile(Request $request)
    {
        if (!$request->hasFile('file')) {
            throw new FileException();
        }
        return UploadService::upload($request->file('file'));
    }
}
