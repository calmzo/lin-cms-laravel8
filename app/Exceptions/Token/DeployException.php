<?php

namespace App\Exceptions\Token;

use App\Utils\CodeResponse;
use Exception;

class DeployException extends Exception
{
    //
    public function __construct(array $codeSponse = CodeResponse::DEPLOY_EXCEPTION, $info = '')
    {
        list($code, $message) = $codeSponse;
        parent::__construct($message = $info ?: $message, $code);
    }

}
