<?php

namespace App\Traits;

use App\Enums\CourseUserEnums;
use App\Models\Course;
use App\Models\User;
use App\Repositories\CourseUserRepository;
use App\Validators\CourseValidator;

trait CourseTrait
{

    /**
     * @var bool
     */
    protected $ownedCourse = false;

    /**
     * @var bool
     */
    protected $joinedCourse = false;

    /**
     * @var Course|null
     */
    protected $courseUser;

    public function checkCourse($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    public function checkCourseCache($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourseCache($id);
    }

    public function setCourseUser(Course $course, User $user)
    {
        $courseUser = null;

        if ($user->id > 0) {
            $courseUserRepo = new CourseUserRepository();
            $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);
        }

        $this->courseUser = $courseUser;

        if ($courseUser) {
            $this->joinedCourse = true;
        }

        if ($course->market_price == 0) {

            $this->ownedCourse = true;

        } elseif ($course->market_price > 0 && $course->vip_price == 0 && $user->vip == 1) {

            $this->ownedCourse = true;

        } elseif ($courseUser && $courseUser->role_type == CourseUserEnums::ROLE_TEACHER) {

            $this->ownedCourse = true;

        } elseif ($courseUser && $courseUser->role_type == CourseUserEnums::ROLE_STUDENT) {

            $sourceTypes = [
                CourseUserEnums::SOURCE_CHARGE,
                CourseUserEnums::SOURCE_IMPORT,
                CourseUserEnums::SOURCE_POINT_REDEEM,
                CourseUserEnums::SOURCE_LUCKY_REDEEM,
            ];
            $case1 = $courseUser->deleted == 0;
            $case2 = $courseUser->expiry_time > time();
            $case3 = in_array($courseUser->source_type, $sourceTypes);

            /**
             * 之前参与过课程，但不再满足条件，视为未参与
             */
            if ($case1 && $case2 && $case3) {
                $this->ownedCourse = true;
            } else {
                $this->joinedCourse = false;
            }
        }
    }

}
