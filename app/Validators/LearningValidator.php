<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Lib\Validators\CommonValidator;
use App\Utils\CodeResponse;

class LearningValidator extends BaseValidator
{

    public function checkPlanId($planId)
    {
        if (!CommonValidator::date($planId, 'Ymd')) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'learning.invalid_plan_id');
        }

        return $planId;
    }

    public function checkRequestId($requestId)
    {
        if (!$requestId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'learning.invalid_request_id');
        }

        return $requestId;
    }

    public function checkIntervalTime($intervalTime)
    {
        $value = $intervalTime;

        /**
         * 兼容秒和毫秒
         */
        if ($value > 1000) {
            $value = intval($value / 1000);
        }

        if ($value < 5) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'learning.invalid_interval_time');
        }

        return $value;
    }

    public function checkPosition($position)
    {
        $value = $position;

        if ($value < 0 || $value > 3 * 3600) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'learning.invalid_position');
        }

        return $value;
    }

}
