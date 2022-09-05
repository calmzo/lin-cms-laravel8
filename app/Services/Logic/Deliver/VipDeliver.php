<?php

namespace App\Services\Logic\Deliver;

use App\Models\User;
use App\Models\Vip;

class VipDeliver
{

    public function handle(Vip $vip, User $user)
    {
        $baseTime = $user->vip_expiry_time > time() ? $user->vip_expiry_time : time();

        $user->vip_expiry_time = strtotime("+{$vip->expiry} months", $baseTime);

        $user->vip = 1;

        $user->save();
    }

}
