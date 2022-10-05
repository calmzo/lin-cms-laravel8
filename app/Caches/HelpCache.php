<?php

namespace App\Caches;

use App\Repositories\HelpRepository;

class HelpCache extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "help:{$id}";
    }

    public function getContent($id = null)
    {
        $helpRepo = new HelpRepository();

        $help = $helpRepo->findById($id);

        return $help ?: null;
    }

}
