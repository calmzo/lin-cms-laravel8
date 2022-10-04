<?php

namespace App\Validators;

use App\Enums\CourseUserEnums;
use App\Exceptions\BadRequestException;
use App\Lib\Validators\CommonValidator;
use App\Repositories\CourseUserRepository;
use App\Utils\CodeResponse;

class CourseUserValidator extends BaseValidator
{

    public function checkRelation($id)
    {
        $courseUserRepo = new CourseUserRepository();

        $courseUser = $courseUserRepo->findById($id);

        if (!$courseUser) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_user.not_found');
        }

        return $courseUser;
    }

    public function checkCourseUser($courseId, $userId)
    {
        $repo = new CourseUserRepository();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if (!$courseUser) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_user.not_found');
        }

        return $courseUser;
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkUser($name)
    {
        $validator = new AccountValidator();

        $account = $validator->checkAccount($name);

        $validator = new UserValidator();

        return $validator->checkUser($account->id);
    }

    public function checkExpiryTime($expiryTime)
    {
        $value = $this->filter->sanitize($expiryTime, ['trim', 'string']);

        if (!CommonValidator::date($value, 'Y-m-d H:i:s')) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_user.invalid_expiry_time');
        }

        return strtotime($value);
    }

    public function checkIfImported($courseId, $userId)
    {
        $repo = new CourseUserRepository();

        $courseUser = $repo->findCourseStudent($courseId, $userId);

        if ($courseUser && $courseUser->source_type == CourseUserEnums::SOURCE_IMPORT) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_user.has_imported');
        }
    }

    public function checkIfReviewed($courseId, $userId)
    {
        $repo = new CourseUserRepository();

        $courseUser = $repo->findCourseUser($courseId, $userId);

//        if ($courseUser && $courseUser->reviewed) {
//            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course_user.has_reviewed');
//        }
    }

}
