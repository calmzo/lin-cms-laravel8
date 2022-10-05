<?php

namespace App\Services\Sync;

use App\Services\BaseService;

class CourseIndexSync extends BaseService
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($courseId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sadd($key, $courseId);

        if ($redis->scard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_course_index';
    }

}
