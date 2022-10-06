<?php

namespace App\Repositories;

use App\Enums\CourseUserEnums;
use App\Enums\ReviewEnums;
use App\Models\Chapter;
use App\Models\ChapterUser;
use App\Models\Course;
use App\Models\CourseRating;
use App\Models\CourseUser;
use App\Models\Review;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseRepository extends BaseRepository
{

    public function findById($id)
    {
        return Course::query()->find($id);
    }

    public function findByIds($ids, $columns = '*')
    {
        return Course::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15): LengthAwarePaginator
    {
        $query = Course::query();


        $fakeId = false;

        if (!empty($where['category_id'])) {
            $where['id'] = $this->getCategoryCourseIds($where['category_id']);
            $fakeId = empty($where['id']);
        }

        if (!empty($where['teacher_id'])) {
            $where['id'] = $this->getTeacherCourseIds($where['teacher_id']);
            $fakeId = empty($where['id']);
        }

        /**
         * 构造空记录条件
         */
        if ($fakeId) $where['id'] = -999;

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $query->whereIn('id', $where['id']);
            } else {
                $query->where('id', $where['id']);
            }
        }

        if (!empty($where['title'])) {
            $query->where('title', 'like', '%' . $where['title'] . '%');
        }

        if (!empty($where['model'])) {
            if (is_array($where['model'])) {
                $query->whereIn('model', $where['model']);
            } else {
                $query->where('model', $where['model']);
            }
        }

        if (!empty($where['level'])) {
            if (is_array($where['level'])) {
                $query->whereIn('level', $where['level']);
            } else {
                $query->where('level', $where['level']);
            }
        }

        if (isset($where['free'])) {
            if ($where['free'] == 1) {
                $query->where('market_price', 0);
            } else {
                $query->where('market_price', '>', 0);
            }
        }

        if (isset($where['featured'])) {
            $query->where('featured', $where['featured']);
        }

        if (isset($where['published'])) {
            $query->where('published', $where['published']);
        }

        if ($sort == 'free') {
            $query->where('market_price', 0);
        } elseif ($sort == 'featured') {
            $query->where('featured', 1);
        } elseif ($sort == 'vip_discount') {
            $query->whereColumn('vip_price', '<', 'market_price');
            $query->where('vip_price', '>', 0);

        } elseif ($sort == 'vip_free') {
            $query->where('market_price', '>', 0);
            $query->where('vip_price', 0);
        }

        switch ($sort) {
            case 'score':
                $query->orderByDesc('score');
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            case 'popular':
                $query->orderByDesc('user_count');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    protected function getCategoryCourseIds($categoryId)
    {
        $categoryIds = is_array($categoryId) ? $categoryId : [$categoryId];

        $repo = new CourseCategoryRepository();

        $rows = $repo->findByCategoryIds($categoryIds);

        $result = [];

        if ($rows->count() > 0) {
            $result = $rows->pluck('course_id')->toArray();
        }

        return $result;
    }

    protected function getTeacherCourseIds($teacherId)
    {
        $teacherIds = is_array($teacherId) ? $teacherId : [$teacherId];

        $repo = new CourseUserRepository();

        $rows = $repo->findByTeacherIds($teacherIds);

        $result = [];

        if ($rows->count() > 0) {
            $result = $rows->pluck('course_id')->toArray();
        }

        return $result;
    }

    public function countCourses()
    {
        return Course::query()->where('published', 1)->count();
    }

    public function countReviews($courseId)
    {
        return Review::query()->where('course_id', $courseId)->where('published', ReviewEnums::PUBLISH_APPROVED)->count();
    }

    public function countLessons($courseId)
    {
        return Chapter::query()->where('course_id', $courseId)->where('parent_id', '>', 0)->count();

    }

    public function countUsers($courseId)
    {
        return CourseUser::query()->where('course_id', $courseId)->where('role_type', CourseUserEnums::ROLE_STUDENT)->count();
    }

    public function findLessons($courseId)
    {
        return Chapter::query()
            ->where('course_id', $courseId)
            ->where('parent_id', '>', 0)
            ->get();
    }

    public function findChapters($courseId)
    {
        return Chapter::query()
            ->where('course_id', $courseId)
            ->get();
    }

    /**
     * @param $courseId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findTeachers($courseId)
    {
        $roleType = CourseUserEnums::ROLE_TEACHER;
        return User::query()->whereHas('courseUser', function ($q) use ($courseId, $roleType) {
            $q->where('course_id', $courseId)->where('role_type', $roleType);
        })->get();
    }

    /**
     * @param $courseId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findCourseRating($courseId)
    {
        return CourseRating::query()->where('course_id', $courseId)->first();
    }

    public function findUserLearnings($courseId, $userId, $planId)
    {
        return ChapterUser::query()
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->where('plan_id', $planId)
            ->get();
    }
}
