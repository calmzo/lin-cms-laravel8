<?php

namespace App\Caches;

use App\Models\Answer;

class MaxAnswerIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_answer_id';
    }

    public function getContent($id = null)
    {
        $answer = Answer::query()->latest('id')->first();

        return $answer->id ?? 0;
    }

}
