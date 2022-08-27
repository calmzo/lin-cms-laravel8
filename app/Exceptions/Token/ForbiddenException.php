<?php

namespace App\Exceptions\Token;

use App\Utils\CodeResponse;
use Exception;

class ForbiddenException extends Exception
{
    //
    public function __construct(array $codeSponse = CodeResponse::FORBIDDEN_EXCEPTION, $info = '')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
