<?php

namespace App\Caches;

use App\Models\User;

class MaxUserIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_user_id';
    }

    public function getContent($id = null)
    {
        $user = User::query()->latest('id')->first();

        return $user->id ?? 0;
    }

}
