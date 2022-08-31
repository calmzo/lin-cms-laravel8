<?php

namespace App\Exceptions;

use App\Utils\CodeResponse;
use Exception;

class BadRequestException extends Exception
{
    //
    public function __construct(array $codeSponse = CodeResponse::NOT_FOUND_EXCEPTION, $info = '')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
