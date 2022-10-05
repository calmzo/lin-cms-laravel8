<?php

namespace App\Services\Logic\Question;

use App\Builders\AnswerListBuilder;
use App\Enums\AnswerEnums;
use App\Repositories\AnswerLikeRepository;
use App\Repositories\AnswerRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\QuestionTrait;

class AnswerListService extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);
        $params['question_id'] = $question->id;
        $params['published'] = AnswerEnums::PUBLISH_APPROVED;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'accepted';
        $limit = $params['limit'] ?? 15;

        $answerRepo = new AnswerRepository();

        $pager = $answerRepo->paginate($params, $sort, $page, $limit);

        return $this->handleAnswers($pager);
    }

    protected function handleAnswers($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $builder = new AnswerListBuilder();

        $answers = collect($paginate->items())->toArray();

        $users = $builder->getUsers($answers);

        $meMappings = $this->getMeMappings($answers);

        $items = [];

        foreach ($answers as $answer) {

            $owner = $users[$answer['user_id']];

            $me = $meMappings[$answer['id']];

            $items[] = [
                'id' => $answer['id'],
                'content' => $answer['content'],
                'anonymous' => $answer['anonymous'],
                'accepted' => $answer['accepted'],
                'like_count' => $answer['like_count'],
                'comment_count' => $answer['comment_count'],
                'create_time' => $answer['create_time'],
                'update_time' => $answer['update_time'],
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

    protected function getMeMappings($answers)
    {
        $uid = AccountLoginTokenService::userId();

        $likeRepo = new AnswerLikeRepository();

        $likedIds = [];

        if ($uid > 0) {
            $likes = $likeRepo->findByUserId($uid);
            $likedIds = $likes->pluck('answer_id')->toArray();
        }

        $result = [];

        foreach ($answers as $answer) {
            $result[$answer['id']] = [
                'liked' => in_array($answer['id'], $likedIds) ? 1 : 0,
                'owned' => $answer['user_id'] == $uid ? 1 : 0,
            ];
        }

        return $result;
    }

}
