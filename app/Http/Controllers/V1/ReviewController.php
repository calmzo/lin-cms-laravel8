<?php

namespace App\Http\Controllers\V1;

use App\Services\ReviewService;
use App\Validates\ArticleFormValidate;
use App\Validates\Review\ReviewFormValidate;

class ReviewController extends BaseController
{
    protected $except = [];

    public function getReview($id)
    {
        $service = new ReviewService();
        $review = $service->getReview($id);
        return $this->success($review);
    }


    /**
     * 评价
     * @param ReviewFormValidate $reviewFormValidate
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ValidateException
     */
    public function createReview(ReviewFormValidate $reviewFormValidate)
    {
        $params = $reviewFormValidate->check();
        $service = new ReviewService();
        $review = $service->createReview($params);
        return $this->success($review);
    }

    public function updateReview($id, ReviewFormValidate $reviewFormValidate)
    {
        $params = $reviewFormValidate->check();
        $service = new ReviewService();
        $review = $service->updateReview($id, $params);
        return $this->success($review);
    }

    public function deleteReview($id)
    {
        $service = new ReviewService();
        $service->deleteReview($id);
        return $this->success([], '删除评价成功');
    }

    public function likeReview($id)
    {
        $service = new ReviewService();
        $res = $service->likeReview($id);
        return $this->success($res);
    }


}
