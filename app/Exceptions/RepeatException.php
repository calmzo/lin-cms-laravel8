<?php

namespace App\Exceptions;

use App\Utils\CodeResponse;
use Exception;

class RepeatException extends Exception
{
    //
    public function __construct($info = '资源已存在')
    {
        list($code, $message) = CodeResponse::REPEATEXCEPTION;
        parent::__construct($message = $info ?: $message, $code);
    }

}
