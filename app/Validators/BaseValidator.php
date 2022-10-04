<?php

namespace App\Validators;


use App\Exceptions\NotFoundException;
use App\Exceptions\Token\ForbiddenException;
use App\Utils\CodeResponse;

class BaseValidator
{

    public function checkSiteStatus()
    {
        $siteInfo = config('site');

        if ($siteInfo['status'] == 'closed') {
            throw new NotFoundException(CodeResponse::NOT_FOUND_EXCEPTION, 'sys.service_unavailable');
        }
    }


    public function checkOwner($userId, $ownerId)
    {
        if ($userId != $ownerId) {
            throw new ForbiddenException(CodeResponse::FORBIDDEN_EXCEPTION, 'sys.forbidden');
        }
    }

}
