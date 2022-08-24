<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    //
    public function __construct(array $codeSponse = [10021, '资源不存在'], $info = '资源不存在')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
