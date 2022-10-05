<?php

namespace App\Caches;

use App\Models\Help;

class MaxHelpIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_help_id';
    }

    public function getContent($id = null)
    {
        $help = Help::query()->latest('id')->first();

        return $help->id ?? 0;
    }

}
