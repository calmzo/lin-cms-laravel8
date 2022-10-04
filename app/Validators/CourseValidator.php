<?php

namespace App\Validators;

use App\Caches\CourseCache;
use App\Caches\MaxCourseIdCache;
use App\Enums\CourseEnums;
use App\Exceptions\BadRequestException;
use App\Lib\Validators\CommonValidator;
use App\Repositories\CourseRepository;
use App\Utils\CodeResponse;

class CourseValidator extends BaseValidator
{

    /**
     * @param int $id
     * @return Course
     * @throws BadRequestException
     */
    public function checkCourseCache($id)
    {
        $this->checkId($id);

        $courseCache = new CourseCache();

        $course = $courseCache->get($id);

        if (!$course) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course.not_found');
        }

        return $course;
    }

    public function checkCourse($id)
    {
        $this->checkId($id);

        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($id);

        if (!$course) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course.not_found');
        }

        return $course;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxCourseIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course.not_found');
        }
    }

    public function checkModel($model)
    {
        $list = CourseEnums::modelTypes();

        if (!array_key_exists($model, $list)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course.invalid_model');
        }

        return $model;
    }

    public function checkLevel($level)
    {
        $list = CourseEnums::levelTypes();

        if (!array_key_exists($level, $list)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course.invalid_level');
        }

        return $level;
    }


    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 5) {
            throw new BadRequestException('course.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('course.title_too_long');
        }

        return $value;
    }

    public function checkDetails($details)
    {
        $value = $this->filter->sanitize($details, ['trim']);

        $length = kg_strlen($value);

        if ($length > 30000) {
            throw new BadRequestException('course.details_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('course.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('course.keywords_too_long');
        }

        $keywords = str_replace(['|', ';', '；', '、', ','], '@', $keywords);
        $keywords = explode('@', $keywords);

        $list = [];

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            if (kg_strlen($keyword) > 1) {
                $list[] = $keyword;
            }
        }

        return implode('，', $list);
    }

    public function checkUserCount($userCount)
    {
        $value = $this->filter->sanitize($userCount, ['trim', 'int']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_user_count');
        }

        return $value;
    }

    public function checkOriginPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_origin_price');
        }

        return $value;
    }

    public function checkMarketPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_market_price');
        }

        return $value;
    }

    public function checkVipPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_vip_price');
        }

        return $value;
    }

    public function checkStudyExpiry($expiry)
    {
        $options = CourseModel::studyExpiryOptions();

        if (!isset($options[$expiry])) {
            throw new BadRequestException('course.invalid_study_expiry');
        }

        return $expiry;
    }

    public function checkRefundExpiry($expiry)
    {
        $options = CourseModel::refundExpiryOptions();

        if (!isset($options[$expiry])) {
            throw new BadRequestException('course.invalid_refund_expiry');
        }

        return $expiry;
    }

    public function checkFeatureStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_feature_status');
        }

        return $status;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_publish_status');
        }

        return $status;
    }

    public function checkPublishAbility(CourseModel $course)
    {
        if ($course->model == CourseModel::MODEL_OFFLINE) return true;

        if ($course->teacher_id == 0) {
            throw new BadRequestException('course.teacher_not_assigned');
        }
    }

}
