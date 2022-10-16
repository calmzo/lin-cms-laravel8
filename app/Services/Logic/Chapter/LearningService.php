<?php

namespace App\Services\Logic\Chapter;

use App\Enums\CourseEnums;
use App\Models\Course;
use App\Models\Learning;
use App\Repositories\UserRepository;
use \App\Services\Logic\LogicService;
use App\Services\Sync\LearningSyncService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ChapterTrait;
use App\Validators\LearningValidator;

class LearningService extends LogicService
{

    use ChapterTrait;

    public function handle($id, $params)
    {
        $chapter = $this->checkChapter($id);

        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $validator = new LearningValidator();

        $data = [
            'course_id' => $chapter->course_id,
            'chapter_id' => $chapter->id,
            'user_id' => $user->id,
            'position' => 0,
        ];

        $data['request_id'] = $validator->checkRequestId($params['request_id']);
        $data['plan_id'] = $validator->checkPlanId($params['plan_id']);

        if ($chapter->model == CourseEnums::MODEL_VOD) {
            $data['position'] = $validator->checkPosition($params['position']);
        }

        $intervalTime = $validator->checkIntervalTime($params['interval_time']);

        $learning = new Learning($data);

        $sync = new LearningSyncService();

        $sync->addItem($learning, $intervalTime);
    }

}
