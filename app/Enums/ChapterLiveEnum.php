<?php

namespace App\Enums;

class ChapterLiveEnum extends BaseEnums
{
    /**
     * 状态类型
     */
    const STATUS_ACTIVE = 1; // 活跃
    const STATUS_INACTIVE = 2; // 静默
    const STATUS_FORBID = 3; // 禁播

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function definition($key = null)
    {
        $list = [];
        return self::getDesc($list, $key);
    }

    public static function generateStreamName($id)
    {
        return "chapter_{$id}";
    }

    public static function parseFromStreamName($streamName)
    {
        return str_replace('chapter_', '', $streamName);
    }
}

