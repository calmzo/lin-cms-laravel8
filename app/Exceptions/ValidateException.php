<?php

namespace App\Exceptions;

use App\Utils\CodeResponse;

class ValidateException extends \Exception
{
    //
    public function __construct(array $codeSponse = CodeResponse::VALIDATE_EXCEPTION, $info = '')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
