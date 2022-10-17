<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Repositories\CourseRepository;
use Illuminate\Console\Command;

class SyncCourseStatTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_course_stat_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $courses = $this->findCourses();

        echo sprintf('pending courses: %s', $courses->count()) . PHP_EOL;

        if ($courses->count() == 0) return;

        echo '------ start sync course stat task ------' . PHP_EOL;

        foreach ($courses as $course) {
            $this->recountUsers($course);
        }

        echo '------ end sync course stat task ------' . PHP_EOL;
    }

    protected function recountUsers(Course $course)
    {
        $courseRepo = new CourseRepository();

        $userCount = $courseRepo->countUsers($course->id);

        $course->user_count = $userCount;

        $course->update();
    }

    protected function findCourses()
    {
        return Course::query()
            ->where('published', 1)
            ->get();
    }
}
