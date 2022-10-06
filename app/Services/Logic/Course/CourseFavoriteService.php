<?php

namespace App\Services\Logic\Course;

use App\Models\Course;
use App\Models\CourseFavorite;
use App\Models\User;
use App\Repositories\CourseFavoriteRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;
use App\Validators\UserLimitValidator;

class CourseFavoriteService extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);
        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);
        $validator = new UserLimitValidator();

        $validator->checkFavoriteLimit($user);

        $favoriteRepo = new CourseFavoriteRepository();

        $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

        if (!$favorite) {
            $favorite = CourseFavorite::query()->create(['course_id' => $course->id, 'user_id' => $user->id]);
        } else {
            if ($favorite->trashed()) {
                $favorite->restore();
            } else {
                $favorite->delete();
            }
        }

        if ($favorite->deleted == 0) {

            $action = 'do';

            $this->incrCourseFavoriteCount($course);
            $this->incrUserFavoriteCount($user);

        } else {

            $action = 'undo';

            $this->decrCourseFavoriteCount($course);
            $this->decrUserFavoriteCount($user);
        }

        return [
            'action' => $action,
            'count' => $course->favorite_count,
        ];
    }

    protected function incrCourseFavoriteCount(Course $course)
    {
        $course->favorite_count += 1;

        $course->save();
    }

    protected function decrCourseFavoriteCount(Course $course)
    {
        if ($course->favorite_count > 0) {
            $course->favorite_count -= 1;
            $course->save();
        }
    }

    protected function incrUserFavoriteCount(User $user)
    {
        $user->favorite_count += 1;

        $user->save();
    }

    protected function decrUserFavoriteCount(User $user)
    {
        if ($user->favorite_count > 0) {
            $user->favorite_count -= 1;
            $user->save();
        }
    }

}
