<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionLevelEnums extends Enum
{
    const LOGIN_REQUIRED = 'loginRequired';

    const GROUP_REQUIRED = 'groupRequired';

    const ADMIN_REQUIRED = 'adminRequired';

}
