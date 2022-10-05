<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;

class LiveValidator extends BaseValidator
{

    public function checkMessage($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'striptags']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('live.msg_too_short');
        }

        if ($length > 255) {
            throw new BadRequestException('live.msg_too_long');
        }

        return $value;
    }

}
