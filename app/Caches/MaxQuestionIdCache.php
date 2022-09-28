<?php

namespace App\Caches;

use App\Models\Question;

class MaxQuestionIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_question_id';
    }

    public function getContent($id = null)
    {
        $question = Question::query()->latest('id')->first();

        return $question->id ?? 0;
    }

}
