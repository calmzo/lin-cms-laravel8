<?php

namespace App\Caches;

use App\Repositories\AnswerRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\ConsultRepository;
use App\Repositories\CourseRepository;
use App\Repositories\PackageRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\TopicRepository;
use App\Repositories\UserRepository;

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

        $courseRepo = new CourseRepository();
        $articleRepo = new ArticleRepository();
        $questionRepo = new QuestionRepository();
        $answerRepo = new AnswerRepository();
        $commentRepo = new CommentRepository();
        $consultRepo = new ConsultRepository();
        $packageRepo = new PackageRepository();
        $reviewRepo = new ReviewRepository();
        $topicRepo = new TopicRepository();
        $userRepo= new UserRepository();

        return [
            'course_count' => $courseRepo->countCourses(),
            'article_count' => $articleRepo->countArticles(),
            'question_count' => $questionRepo->countQuestions(),
            'answer_count' => $answerRepo->countAnswers(),
            'comment_count' => $commentRepo->countComments(),
            'consult_count' => $consultRepo->countConsults(),
            'vip_count' => $userRepo->countVipUsers(),
            'package_count' => $packageRepo->countPackages(),
            'review_count' => $reviewRepo->countReviews(),
            'topic_count' => $topicRepo->countTopics(),
            'user_count' => $userRepo->countUsers(),
        ];
    }

}
