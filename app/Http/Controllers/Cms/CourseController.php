<?php

namespace App\Http\Controllers\Cms;

use App\Services\CourseService;
use App\Services\Admin\CategoryService;


class CourseController extends BaseController
{
    protected $only = [];

    /**
     * @groupRequired
     * @permission('分类','课程管理')
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function category()
    {
        $service = new CategoryService();
        $list = $service->courseCategoryList();
        return $this->success($list);
    }

    /**
     * @groupRequired
     * @permission('搜索课程','课程管理')
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $courseService = new CourseService();

        $xmCategories = $courseService->getXmCategories(0);
        $xmTeachers = $courseService->getXmTeachers(0);
        $modelTypes = $courseService->getModelTypes();
        $levelTypes = $courseService->getLevelTypes();

        $this->view->setVar('xm_categories', $xmCategories);
        $this->view->setVar('xm_teachers', $xmTeachers);
        $this->view->setVar('model_types', $modelTypes);
        $this->view->setVar('level_types', $levelTypes);
    }

    /**
     * @groupRequired
     * @permission('课程列表','课程管理')
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $courseService = new CourseService();

        $pager = $courseService->getCourses();


        $this->view->setVar('pager', $pager);
    }

    public function add()
    {
        $service = new CourseService();
        $res = $service->getModelTypes();
        return $this->success($res);
    }


    /**
     * @groupRequired
     * @permission('添加课程','课程管理')
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $courseService = new CourseService();

        $course = $courseService->createCourse();

        $location = $this->url->get([
            'for' => 'admin.course.edit',
            'id' => $course->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建课程成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.course.edit")
     */
    public function editAction($id)
    {
        $courseService = new CourseService();

        $course = $courseService->getCourse($id);
        $xmTeachers = $courseService->getXmTeachers($id);
        $xmCategories = $courseService->getXmCategories($id);
        $xmCourses = $courseService->getXmCourses($id);
        $studyExpiryOptions = $courseService->getStudyExpiryOptions();
        $refundExpiryOptions = $courseService->getRefundExpiryOptions();

        $this->view->setVar('course', $course);
        $this->view->setVar('xm_teachers', $xmTeachers);
        $this->view->setVar('xm_categories', $xmCategories);
        $this->view->setVar('xm_courses', $xmCourses);
        $this->view->setVar('study_expiry_options', $studyExpiryOptions);
        $this->view->setVar('refund_expiry_options', $refundExpiryOptions);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.course.update")
     */
    public function updateAction($id)
    {
        $courseService = new CourseService();

        $courseService->updateCourse($id);

        $content = ['msg' => '更新课程成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.course.delete")
     */
    public function deleteAction($id)
    {
        $courseService = new CourseService();

        $courseService->deleteCourse($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除课程成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.course.restore")
     */
    public function restoreAction($id)
    {
        $courseService = new CourseService();

        $courseService->restoreCourse($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原课程成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/chapters", name="admin.course.chapters")
     */
    public function chaptersAction($id)
    {
        $courseService = new CourseService();

        $course = $courseService->getCourse($id);
        $chapters = $courseService->getChapters($id);

        $this->view->setVar('course', $course);
        $this->view->setVar('chapters', $chapters);
    }
}
