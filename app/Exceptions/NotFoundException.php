<?php

namespace App\Exceptions;

use App\Utils\CodeResponse;
use Exception;

class NotFoundException extends Exception
{
    //
    public function __construct($info = '资源不存在')
    {
        list($code, $message) = CodeResponse::EXCEPTION;
        parent::__construct($message = $info ?: $message, $code);
    }

}
