<?php

namespace App\Exceptions;

use Exception;

class AuthFailedException extends Exception
{
    //
    public function __construct(array $codeSponse = [403, '用户身份认证失败'], $info = '用户身份认证失败')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
