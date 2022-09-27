<?php

namespace App\Enums;

class CourseEnums extends BaseEnums
{
    const ERROR_STR = '未知';

    /**
     * 模型
     */
    const MODEL_VOD = 1; // 点播
    const MODEL_LIVE = 2; // 直播
    const MODEL_READ = 3; // 图文
    const MODEL_OFFLINE = 4; // 面授

    /**
     * 级别
     */
    const LEVEL_ENTRY = 1; // 入门
    const LEVEL_JUNIOR = 2; // 初级
    const LEVEL_MEDIUM = 3; // 中级
    const LEVEL_SENIOR = 4; // 高级


    public static function modelTypes($key = null)
    {
        $list = [
            self::MODEL_VOD => '点播',
            self::MODEL_LIVE => '直播',
            self::MODEL_READ => '图文',
            self::MODEL_OFFLINE => '面授',
        ];
        return self::getDesc($list, $key);
    }

    public static function levelTypes($key = null)
    {
        $list = [
            self::LEVEL_ENTRY => '入门',
            self::LEVEL_JUNIOR => '初级',
            self::LEVEL_MEDIUM => '中级',
            self::LEVEL_SENIOR => '高级',
        ];
        return self::getDesc($list, $key);
    }
}
