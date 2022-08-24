<?php

namespace App\Exceptions;

use Exception;

class OperationException extends Exception
{
    //
    public function __construct(array $codeSponse = [10001, '权限不足，请联系管理员'], $info = '权限不足，请联系管理员')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
