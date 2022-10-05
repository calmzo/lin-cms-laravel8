<?php


namespace App\Services\Logic\Question;

use App\Builders\QuestionListBuilder;
use App\Enums\QuestionEnums;
use App\Repositories\QuestionRepository;
use App\Services\Logic\LogicService;

class QuestionListService extends LogicService
{

    public function handle($params)
    {
        $params['published'] = QuestionEnums::PUBLISH_APPROVED;

        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $questionRepo = new QuestionRepository();

        $paginate = $questionRepo->paginate($params, $sort, $page, $limit);

        return $this->handleQuestions($paginate);
    }

    public function handleQuestions($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new QuestionListBuilder();

        $categories = $builder->getCategories();

        $questions = collect($paginate->items())->toArray();

        $users = $builder->getUsers($questions);

        $items = [];

        foreach ($questions as &$question) {

            $category = $categories[$question['category_id']] ?? (object)[];

            $owner = $users[$question['user_id']] ?? (object)[];

            $lastReplier = $users[$question['last_replier_id']] ?? (object)[];

            $items[] = [
                'id' => $question['id'],
                'title' => $question['title'],
                'cover' => $question['cover'],
                'summary' => $question['summary'],
                'tags' => $question['tags'],
                'bounty' => $question['bounty'],
                'anonymous' => $question['anonymous'],
                'closed' => $question['closed'],
                'solved' => $question['solved'],
                'published' => $question['published'],
                'view_count' => $question['view_count'],
                'like_count' => $question['like_count'],
                'answer_count' => $question['answer_count'],
                'comment_count' => $question['comment_count'],
                'favorite_count' => $question['favorite_count'],
                'last_reply_time' => $question['last_reply_time'],
                'create_time' => $question['create_time'],
                'update_time' => $question['update_time'],
                'last_replier' => $lastReplier,
                'category' => $category,
                'owner' => $owner,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
