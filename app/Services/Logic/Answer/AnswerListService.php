<?php

namespace App\Services\Logic\Answer;

use App\Builders\AnswerListBuilder;
use App\Repositories\AnswerRepository;
use App\Services\Logic\LogicService;

class AnswerListService extends LogicService
{

    public function handle($params)
    {
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $answerRepo = new AnswerRepository();

        $paginate = $answerRepo->paginate($params, $sort, $page, $limit);

        return $this->handleAnswers($paginate);
    }

    public function handleAnswers($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new AnswerListBuilder();

        $answers = collect($paginate->items())->toArray();

        $questions = $builder->getQuestions($answers);

        $users = $builder->getUsers($answers);

        $items = [];

        foreach ($answers as $answer) {

            $question = $questions[$answer['question_id']] ?? (object)[];
            $owner = $users[$answer['user_id']] ?? (object)[];

            $items[] = [
                'id' => $answer['id'],
                'summary' => $answer['summary'],
                'published' => $answer['published'],
                'accepted' => $answer['accepted'],
                'comment_count' => $answer['comment_count'],
                'like_count' => $answer['like_count'],
                'create_time' => $answer['create_time'],
                'update_time' => $answer['update_time'],
                'question' => $question,
                'owner' => $owner,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
