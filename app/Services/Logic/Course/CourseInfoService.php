<?php

namespace App\Services\Logic\Course;

use App\Enums\CourseEnums;
use App\Models\Course;
use App\Models\User;
use App\Repositories\CourseFavoriteRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;

class CourseInfoService extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);

        $this->setCourseUser($course, $user);

        return $this->handleCourse($course, $user);
    }

    protected function handleCourse(Course $course, User $user)
    {
        $service = new BasicInfoService();
        $result = $service->handleBasicInfo($course);

        $me = [
            'plan_id' => 0,
            'allow_order' => 0,
            'allow_reward' => 0,
            'joined' => 0,
            'owned' => 0,
            'reviewed' => 0,
            'favorited' => 0,
            'progress' => 0,
        ];

        $me['joined'] = $this->joinedCourse ? 1 : 0;
        $me['owned'] = $this->ownedCourse ? 1 : 0;

        $caseOwned = $this->ownedCourse == false;
        $casePrice = $course->market_price > 0;

        /**
         * 过期直播不允许购买
         */
        if ($course->model == CourseEnums::MODEL_LIVE) {
            $caseModel = $course->attrs['end_date'] < date('Y-m-d');
        } else {
            $caseModel = true;
        }

        $me['allow_order'] = $caseOwned && $casePrice && $caseModel ? 1 : 0;
        $me['allow_reward'] = $course->market_price == 0 ? 1 : 0;

        if ($user->id > 0) {

            $favoriteRepo = new CourseFavoriteRepository();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            if ($this->courseUser) {
                $me['reviewed'] = $this->courseUser->reviewed ? 1 : 0;
                $me['progress'] = $this->courseUser->progress ? 1 : 0;
                $me['plan_id'] = $this->courseUser->plan_id;
            }
        }

        $result['me'] = $me;

        return $result;
    }

}
