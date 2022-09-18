<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GroupLevelEnums extends Enum
{
    const NONE = '';
    const ROOT = 1;
    const GUEST = 2;
    const USER = 3;

}
