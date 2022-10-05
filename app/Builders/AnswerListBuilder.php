<?php

namespace App\Builders;

use App\Repositories\QuestionRepository;
use App\Services\QuestionService;
use App\Services\UserService;

class AnswerListBuilder
{

    public function handleQuestions(array $answers)
    {
        $questions = $this->getQuestions($answers);

        foreach ($answers as $key => $answer) {
            $answers[$key]['question'] = $questions[$answer['question_id']] ?? (object)[];
        }

        return $answers;
    }

    public function handleUsers(array $answers)
    {
        $users = $this->getUsers($answers);

        foreach ($answers as $key => $answer) {
            $answers[$key]['owner'] = $users[$answer['user_id']] ?? (object)[];
        }

        return $answers;
    }

    public function getQuestions(array $answers)
    {
        $ids = array_column_unique($answers, 'question_id');

        $questionRepo = new QuestionRepository();

        $questions = $questionRepo->findByIds($ids, ['id', 'title']);
        return $questions->keyBy('id')->toArray();
    }

    public function getUsers(array $answers)
    {
        $ids = array_column_unique($answers, 'user_id');

        $userService = new UserService();

        $users = $userService->findUserByIds($ids);

        return $users->keyBy('id')->toArray();
    }

}
