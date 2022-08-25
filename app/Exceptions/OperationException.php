<?php

namespace App\Exceptions;

use App\Utils\CodeResponse;
use Exception;

class OperationException extends Exception
{
    //
    public function __construct(array $codeSponse = CodeResponse::OPERATION_EXCEPTION, $info = '')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
