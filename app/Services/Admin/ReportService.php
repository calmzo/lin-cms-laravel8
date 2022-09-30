<?php

namespace App\Services\Admin;

use App\Builders\AnswerListBuilder;
use App\Builders\ArticleListBuilder;
use App\Builders\QuestionListBuilder;
use App\Repositories\AnswerRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportService extends BaseService
{

    public function getArticles($params)
    {
        $page = $params['page'] ?? 0;
        $limit = $params['count'] ?? 15;
        list($page, $count) = paginateFormat($page, $limit);
        $articleRepo = new ArticleRepository();
        $paginate = $articleRepo->paginate($params, 'reported', $page, $count);
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
        $count = $params['count'] ?? 15;
        list($page, $count) = paginateFormat($page, $count);
        $questionRepo = new QuestionRepository();
        $paginate = $questionRepo->paginate($params, 'reported', $page, $count);
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
        $count = $params['count'] ?? 15;
        list($page, $count) = paginateFormat($page, $count);
        $answerRepo = new AnswerRepository();
        $paginate = $answerRepo->paginate($params, 'reported', $page, $count);
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
        $count = $params['count'] ?? 15;
        list($page, $count) = paginateFormat($page, $count);
        $commentRepo = new CommentRepository();
        $paginate = $commentRepo->paginate($params, 'reported', $page, $count);
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
