<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
{
    //
    public function __construct(array $codeSponse = [10020, '账户不存在'], $info = '账户不存在')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
