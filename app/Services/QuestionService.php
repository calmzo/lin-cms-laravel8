<?php

namespace App\Services;

use App\Enums\QuestionEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Question\AnswerListService;
use App\Services\Logic\Question\CategoryListService;
use App\Services\Logic\Question\CommentListService;
use App\Services\Logic\Question\QuestionDeleteService;
use App\Services\Logic\Question\QuestionFavoriteService;
use App\Services\Logic\Question\QuestionInfoService;
use App\Services\Logic\Question\QuestionListService;

class QuestionService
{
    public function getQuestion($id)
    {
        $service = new QuestionInfoService();

        $question = $service->handle($id);

        $approved = $question['published'] == QuestionEnums::PUBLISH_APPROVED;
        $owned = $question['me']['owned'] == 1;

        if (!$approved && !$owned) {
            throw new NotFoundException();
        }
        return ['question' => $question];

    }

    public function getQuestions($params)
    {
        $service = new QuestionListService();

        $pager = $service->handle($params);

        return $pager;

    }


    public function getCategories()
    {
        $service = new CategoryListService();

        $categories = $service->handle();

        return ['categories' => $categories];

    }

    public function getAnswers($id, $params)
    {
        $service = new AnswerListService();

        $pager = $service->handle($id, $params);

        return $pager;

    }

    public function getComments($id, $params)
    {
        $service = new CommentListService();

        $pager = $service->handle($id, $params);

        return $pager;

    }


    public function deleteQuestion($id)
    {
        $service = new QuestionDeleteService();
        $question = $service->handle($id);

        return $question;

    }

    public function favoriteQuestion($id)
    {
        $service = new QuestionFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return ['data' => $data, 'msg' => $msg];

    }
}
