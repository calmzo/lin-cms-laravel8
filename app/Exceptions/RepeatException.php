<?php

namespace App\Exceptions;

use Exception;

class RepeatException extends Exception
{
    //
    public function __construct(array $codeSponse = [400, '资源已存在'], $info = '资源已存在')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
