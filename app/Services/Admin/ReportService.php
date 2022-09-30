<?php

namespace App\Services\Admin;

use App\Builders\AnswerListBuilder;
use App\Builders\ArticleListBuilder;
use App\Builders\QuestionListBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportService extends BaseService
{

    public function getArticles($params)
    {
        $page = $params['page'] ?? 0;
        $limit = $params['count'] ?? 15;

        $articleService = new ArticleService();
        $paginate = $articleService->paginate($params, 'reported', $page, $limit);
        return $this->handleArticles($paginate);
    }

    protected function handleArticles(LengthAwarePaginator $paginate)
    {

        if ($paginate->total() > 0) {

            $builder = new ArticleListBuilder();
            $items = collect($paginate->items())->toArray();
            $pipeA = $builder->handleCategories($items);
            $pipeB = $builder->handleUsers($pipeA);
            $paginate = $this->newPaginator($paginate, $pipeB);
        }

        return $paginate;
    }


    public function getQuestions($params)
    {
        $page = $params['page'] ?? 0;
        $limit = $params['count'] ?? 15;

        $questionService = new QuestionService();

        $paginate = $questionService->paginate($params, 'reported', $page, $limit);
        return $this->handleQuestions($paginate);

    }

    protected function handleQuestions(LengthAwarePaginator $paginate)
    {

        if ($paginate->total() > 0) {

            $builder = new QuestionListBuilder();
            $items = collect($paginate->items())->toArray();
            $pipeA = $builder->handleCategories($items);
            $pipeB = $builder->handleUsers($pipeA);
            $paginate = $this->newPaginator($paginate, $pipeB);
        }

        return $paginate;
    }

    public function getAnswers($params)
    {
        $page = $params['page'] ?? 0;
        $limit = $params['count'] ?? 15;

        $answerService = new AnswerService();
        $paginate = $answerService->paginate($params, 'reported', $page, $limit);
        return $this->handleAnswers($paginate);
    }

    protected function handleAnswers(LengthAwarePaginator $paginate)
    {

        if ($paginate->total() > 0) {


            $builder = new AnswerListBuilder();

            $items = collect($paginate->items())->toArray();

            $pipeA = $builder->handleQuestions($items);
            $pipeB = $builder->handleUsers($pipeA);

            $paginate = $this->newPaginator($paginate, $pipeB);
        }

        return $paginate;
    }


    public function getComments($params)
    {
        $page = $params['page'] ?? 0;
        $limit = $params['count'] ?? 15;

        $answerService = new AnswerService();
        $paginate = $answerService->paginate($params, 'reported', $page, $limit);
        return $this->handleComments($paginate);
    }

    protected function handleComments(LengthAwarePaginator $paginate)
    {
        if ($paginate->total() > 0) {

            $builder = new AnswerListBuilder();

            $items = collect($paginate->items())->toArray();

            $pipeA = $builder->handleUsers($items);
            $paginate = $this->newPaginator($paginate, $pipeA);
        }

        return $paginate;
    }
}
