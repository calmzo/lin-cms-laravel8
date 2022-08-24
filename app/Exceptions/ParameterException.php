<?php

namespace App\Exceptions;

use Exception;

class ParameterException extends Exception
{
    //
    public function __construct(array $codeSponse = [10030, '参数错误'], $info = '参数错误')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
