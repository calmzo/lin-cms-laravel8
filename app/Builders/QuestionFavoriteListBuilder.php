<?php

namespace App\Builders;

use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;

class QuestionFavoriteListBuilder
{

    public function handleQuestions(array $relations)
    {
        $questions = $this->getQuestions($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['question'] = $questions[$value['question_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getQuestions(array $relations)
    {
        $ids = array_column_unique($relations, 'question_id');

        $questionRepo = new QuestionRepository();

        $columns = [
            'id', 'title', 'cover',
            'view_count', 'like_count',
            'answer_count', 'favorite_count',
        ];

        $questions = $questionRepo->findByIds($ids, $columns);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($questions->toArray() as $question) {

//            if (!empty($question['cover']) && !Text::startsWith($question['cover'], 'http')) {
//                $question['cover'] = $baseUrl . $question['cover'];
//            }

            $result[$question['id']] = $question;
        }

        return $result;
    }

    public function getUsers(array $relations)
    {
        $ids = array_column_unique($relations, 'user_id');

        $userRepo = new UserRepository();

        $users = $userRepo->findShallowUserByIds($ids);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
//            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
