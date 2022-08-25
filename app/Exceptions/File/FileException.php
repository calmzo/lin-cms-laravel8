<?php

namespace App\Exceptions\File;

use App\Utils\CodeResponse;
use Exception;
class FileException extends Exception
{
    //
    public function __construct($info = '文件体积过大')
    {
        list($code, $message) = CodeResponse::FILEEXCEPTION;
        parent::__construct($message = $info ?: $message, $code);
    }

}
