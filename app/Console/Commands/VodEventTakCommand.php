<?php

namespace App\Console\Commands;

use App\Enums\ChapterEnum;
use App\Lib\VodService;
use App\Repositories\ChapterRepository;
use App\Services\CourseStatService;
use Illuminate\Console\Command;

class VodEventTakCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vod_event_task';

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
        $events = $this->pullEvents();

        if (!$events) return;

        $handles = [];

        foreach ($events as $event) {

            $result = true;

            if ($event['EventType'] == 'NewFileUpload') {
                $result = $this->handleNewFileUploadEvent($event);
            } elseif ($event['EventType'] == 'ProcedureStateChanged') {
                $result = $this->handleProcedureStateChangedEvent($event);
            } elseif ($event['EventType'] == 'FileDeleted') {
                $result = $this->handleFileDeletedEvent($event);
            }

            if ($result) {
                $handles[] = $event['EventHandle'];
            }
        }

        if (count($handles) > 0) {
            $this->confirmEvents($handles);
        }
    }


    protected function handleNewFileUploadEvent($event)
    {
        $fileId = $event['FileUploadEvent']['FileId'] ?? 0;
        $width = $event['FileUploadEvent']['MetaData']['Height'] ?? 0;
        $height = $event['FileUploadEvent']['MetaData']['Width'] ?? 0;
        $duration = $event['FileUploadEvent']['MetaData']['Duration'] ?? 0;

        if ($fileId == 0) return false;

        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return false;

        $attrs = $chapter->attrs;

        /**
         * 获取不到时长，尝试通过主动查询获取
         */
        if ($duration == 0) {
            $duration = $this->getFileDuration($fileId);
        }

        $isVideo = $width > 0 && $height > 0;

        $vodService = new VodService();

        if ($duration > 0) {
            if ($isVideo) {
                $vodService->createTransVideoTask($fileId);
            } else {
                $vodService->createTransAudioTask($fileId);
            }
            $attrs['file']['status'] = ChapterEnum::FS_TRANSLATING;
        } else {
            $attrs['file']['status'] = ChapterEnum::FS_FAILED;
        }

        $attrs['duration'] = (int)$duration;

        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseVodAttrs($chapter->course_id);

        return true;
    }

    protected function handleProcedureStateChangedEvent($event)
    {
        $fileId = $event['ProcedureStateChangeEvent']['FileId'] ?? 0;

        if ($fileId == 0) return false;

        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return false;

        $attrs = $chapter->attrs;

        /**
         * 获取不到时长，尝试通过接口获得
         */
        if ($attrs['duration'] == 0) {
            $attrs['duration'] = $this->getFileDuration($fileId);
        }

        $failCount = $successCount = 0;

        $processResult = $event['ProcedureStateChangeEvent']['MediaProcessResultSet'] ?? [];

        if ($processResult) {
            foreach ($processResult as $item) {
                if ($item['Type'] == 'Transcode') {
                    if ($item['TranscodeTask']['Status'] == 'SUCCESS') {
                        $successCount++;
                    } elseif ($item['TranscodeTask']['Status'] == 'FAIL') {
                        $failCount++;
                    }
                }
            }
        }

        $fileStatus = ChapterEnum::FS_TRANSLATING;

        if (!$processResult) {
            $fileStatus = ChapterEnum::FS_FAILED;
        }

        /**
         * 当有一个成功标记为成功
         */
        if ($successCount > 0) {
            $fileStatus = ChapterEnum::FS_TRANSLATED;
        } elseif ($failCount > 0) {
            $fileStatus = ChapterEnum::FS_FAILED;
        }

        $attrs['file']['id'] = $fileId;
        $attrs['file']['status'] = $fileStatus;

        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseVodAttrs($chapter->course_id);

        return true;
    }

    protected function pullEvents()
    {
        $vodService = new VodService();

        return $vodService->pullEvents();
    }

    protected function getFileDuration($fileId)
    {
        $service = new VodService();

        $metaInfo = $service->getOriginVideoInfo($fileId);

        return $metaInfo['duration'] ?? 0;
    }

    protected function updateCourseVodAttrs($courseId)
    {
        $courseStats = new CourseStatService();

        $courseStats->updateVodAttrs($courseId);
    }

    protected function handleFileDeletedEvent($event)
    {
        return true;
    }


}
