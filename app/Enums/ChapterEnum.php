<?php

namespace App\Enums;

class ChapterEnum extends BaseEnums
{

    /**
     * 文件状态
     */
    const FS_PENDING = 'pending'; // 待上传
    const FS_UPLOADED = 'uploaded'; // 已上传
    const FS_TRANSLATING = 'translating'; // 转码中
    const FS_TRANSLATED = 'translated'; // 已转码
    const FS_FAILED = 'failed'; // 已失败

    /**
     * 推流状态
     */
    const SS_ACTIVE = 'active'; // 活跃
    const SS_INACTIVE = 'inactive'; // 静默
    const SS_FORBID = 'forbid'; // 禁播

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function definition($key = null)
    {
        $list = [];
        return self::getDesc($list, $key);
    }
}

