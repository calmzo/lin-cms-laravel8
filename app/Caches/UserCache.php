<?php

namespace App\Caches;

use App\Models\User;

class UserCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "user:{$id}";
    }

    public function getContent($id = null)
    {
        $user = User::query()->find($id);

        return $user ?: null;
    }

}
