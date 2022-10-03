<?php

namespace App\Services\Logic\User\Console;

use App\Builders\ArticleFavoriteListBuilder;
use App\Builders\CourseFavoriteListBuilder;
use App\Builders\QuestionFavoriteListBuilder;
use App\Repositories\ArticleFavoriteRepository;
use App\Repositories\CourseFavoriteRepository;
use App\Repositories\QuestionFavoriteRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleFavoriteListService extends LogicService
{

    public function handle($params)
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;
        $type = $params['type'] ?? 'course';
        if ($type == 'course') {

            $favoriteRepo = new CourseFavoriteRepository();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            return $this->handleCourses($pager);

        } elseif ($type == 'article') {

            $favoriteRepo = new ArticleFavoriteRepository();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);
            return $this->handleArticles($pager);

        } elseif ($type == 'question') {

            $favoriteRepo = new QuestionFavoriteRepository();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            return $this->handleQuestions($pager);
        }
    }

    protected function handleCourses($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new CourseFavoriteListBuilder();

        $relations = collect($paginate->items())->toArray();

        $courses = $builder->getCourses($relations);

        $items = [];

        foreach ($relations as &$relation) {
            $course = $courses[$relation['course_id']] ?? new \stdClass();
            $items[] = $course;
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

    protected function handleArticles($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new ArticleFavoriteListBuilder();

        $relations = collect($paginate->items())->toArray();

        $articles = $builder->getArticles($relations);

        $items = [];

        foreach ($relations as $relation) {
            $article = $articles[$relation['article_id']] ?? new \stdClass();
            $items[] = $article;
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

    protected function handleQuestions($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new QuestionFavoriteListBuilder();

        $relations = collect($paginate->items())->toArray();

        $questions = $builder->getQuestions($relations);

        $items = [];

        foreach ($relations as $relation) {
            $question = $questions[$relation['question_id']] ?? new \stdClass();
            $items[] = $question;
        }
        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
