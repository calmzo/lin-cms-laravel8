<?php

namespace App\Caches;

use App\Services\AnswerService;
use App\Services\ArticleService;
use App\Services\CommentService;
use App\Services\ConsultService;
use App\Services\CourseService;
use App\Services\PackageService;
use App\Services\QuestionService;
use App\Services\ReviewService;
use App\Services\TopicService;
use App\Services\UserService;

class SiteGlobalStatCache extends Cache
{

    protected $lifetime = 15 * 60;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'site_global_stat';
    }

    public function getContent($id = null)
    {

        $courseService = new CourseService();
        $articleService = new ArticleService();
        $questionService = new QuestionService();
        $answerService = new AnswerService();
        $commentService = new CommentService();
        $consultService = new ConsultService();
        $packageService = new PackageService();
        $reviewService = new ReviewService();
        $topicService = new TopicService();
        $userService = new UserService();

        return [
            'course_count' => $courseService->countCourses(),
            'article_count' => $articleService->countArticles(),
            'question_count' => $questionService->countQuestions(),
            'answer_count' => $answerService->countAnswers(),
            'comment_count' => $commentService->countComments(),
            'consult_count' => $consultService->countConsults(),
            'vip_count' => $userService->countVipUsers(),
            'package_count' => $packageService->countPackages(),
            'review_count' => $reviewService->countReviews(),
            'topic_count' => $topicService->countTopics(),
            'user_count' => $userService->countUsers(),
        ];
    }

}
