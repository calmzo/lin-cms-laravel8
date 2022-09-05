<?php

namespace App\Enums;

class CourseEnums
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


}
