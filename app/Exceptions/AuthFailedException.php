<?php

namespace App\Exceptions;

use App\Utils\CodeResponse;
use Exception;

class AuthFailedException extends Exception
{
    //
    public function __construct($info = '用户身份认证失败')
    {
        list($code, $message) = CodeResponse::EXCEPTION;
        parent::__construct($message = $info ?: $message, $code);
    }

}
