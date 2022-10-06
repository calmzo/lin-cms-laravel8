<?php

namespace App\Services\Logic\Course;

use App\Enums\ConsultEnums;
use App\Repositories\ConsultRepository;
use App\Services\Logic\LogicService;
use App\Traits\CourseTrait;

class ConsultListService extends LogicService
{

    use CourseTrait;
    use ConsultListTrait;

    public function handle($id, $params)
    {
        $course = $this->checkCourse($id);

        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $params = [
            'course_id' => $course->id,
            'published' => ConsultEnums::PUBLISH_APPROVED,
            'private' => 0,
        ];

        $consultRepo = new ConsultRepository();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

}
