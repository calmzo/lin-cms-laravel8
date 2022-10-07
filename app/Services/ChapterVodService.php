<?php

namespace App\Services;

use App\Repositories\ChapterRepository;
use App\Lib\VodService;

class ChapterVodService extends BaseService
{
    public function getPlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepository();

        $vod = $chapterRepo->findChapterVod($chapterId);

        /**
         * 腾讯云点播优先
         */
        if ($vod->file_id) {
            $playUrls = $this->getCosPlayUrls($chapterId);
        } else {
            $playUrls = $this->getRemotePlayUrls($chapterId);
        }

        /**
         *过滤播放地址为空的条目
         */
        foreach ($playUrls as $key => $value) {
            if (empty($value['url'])) unset($playUrls[$key]);
        }

        return $playUrls;
    }

    public function getCosPlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepository();

        $vod = $chapterRepo->findChapterVod($chapterId);

        if (empty($vod->file_transcode)) return [];

        $vodService = new VodService();

        $result = [];

        foreach ($vod->file_transcode as $key => $file) {
            $file['url'] = $vodService->getPlayUrl($file['url']);
            $type = $this->getDefinitionType($file['height']);
            $result[$type] = $file;
        }

        return $result;
    }

    public function getRemotePlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepository();

        $vod = $chapterRepo->findChapterVod($chapterId);

        $result = [
            'hd' => ['url' => ''],
            'sd' => ['url' => ''],
            'fd' => ['url' => ''],
        ];

        if (!empty($vod->file_remote)) {
            $result = $vod->file_remote;
        }

        return $result;
    }

    protected function getDefinitionType($height)
    {
        $default = 'sd';

        $vodTemplates = $this->getVodTemplates();

        foreach ($vodTemplates as $key => $template) {
            if ($height >= $template['height']) {
                return $key;
            }
        }

        return $default;
    }

    protected function getVodTemplates()
    {
        return [
            'hd' => ['height' => 1080, 'rate' => 2500],
            'sd' => ['height' => 720, 'rate' => 1800],
            'fd' => ['height' => 540, 'rate' => 1000],
        ];
    }

}
