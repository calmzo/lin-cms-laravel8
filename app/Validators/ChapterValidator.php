<?php

namespace App\Validators;

use App\Caches\ChapterCache;
use App\Caches\MaxChapterIdCache;
use App\Enums\CourseEnums;
use App\Exceptions\BadRequestException;
use App\Repositories\ChapterRepository;
use App\Utils\CodeResponse;
use App\Models\Chapter;

class ChapterValidator extends BaseValidator
{

    /**
     * @param int $id
     * @return Chapter
     * @throws BadRequestException
     */
    public function checkChapterCache($id)
    {
        $this->checkId($id);

        $chapterCache = new ChapterCache();

        $chapter = $chapterCache->get($id);

        if (!$chapter) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.not_found');
        }

        return $chapter;
    }

    public function checkChapterVod($id)
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepository();

        $chapterVod = $chapterRepo->findChapterVod($id);

        if (!$chapterVod) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.vod_not_found');
        }

        return $chapterVod;
    }

    public function checkChapterLive($id)
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepository();

        $chapterLive = $chapterRepo->findChapterLive($id);

        if (!$chapterLive) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.live_not_found');
        }

        return $chapterLive;
    }

    public function checkChapterRead($id)
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepository();

        $chapterRead = $chapterRepo->findChapterRead($id);

        if (!$chapterRead) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.read_not_found');
        }

        return $chapterRead;
    }

    public function checkChapter($id)
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.not_found');
        }

        return $chapter;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxChapterIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.not_found');
        }
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkParent($id)
    {
        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.parent_not_found');
        }

        return $chapter;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('chapter.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('chapter.title_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('chapter.summary_too_long');
        }

        return $value;
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('chapter.invalid_priority');
        }

        return $value;
    }

    public function checkFreeStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.invalid_free_status');
        }

        return $status;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'course.invalid_publish_status');
        }

        return $status;
    }

    public function checkPublishAbility(Chapter $chapter)
    {
        $attrs = $chapter->attrs;

        if ($chapter->model == CourseEnums::MODEL_VOD) {
            if ($attrs['duration'] == 0) {
                throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.vod_not_ready');
            }
        } elseif ($chapter->model == CourseEnums::MODEL_LIVE) {
            if ($attrs['start_time'] == 0) {
                throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.live_time_empty');
            }
        } elseif ($chapter->model == CourseEnums::MODEL_READ) {
            if ($attrs['word_count'] == 0) {
                throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.read_not_ready');
            }
        } elseif ($chapter->model == CourseEnums::MODEL_OFFLINE) {
            if ($attrs['start_time'] == 0) {
                throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.offline_time_empty');
            }
        }
    }

    public function checkDeleteAbility(Chapter $chapter)
    {
        $chapterRepo = new ChapterRepository();

        $chapters = $chapterRepo->findAll([
            'parent_id' => $chapter->id
        ]);

        if ($chapters->count() > 0) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'chapter.child_existed');
        }
    }

}
