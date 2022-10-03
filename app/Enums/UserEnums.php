<?php

namespace App\Enums;

class UserEnums extends BaseEnums
{

    /**
     * 性别类型
     */
    const GENDER_MALE = 1; // 男
    const GENDER_FEMALE = 2; // 女
    const GENDER_NONE = 3; // 保密

    /**
     * 教学角色
     */
    const EDU_ROLE_STUDENT = 1; // 学员
    const EDU_ROLE_TEACHER = 2; // 讲师

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function genderTypes($key = null)
    {
        $list = [
            self::GENDER_MALE => '男',
            self::GENDER_FEMALE => '女',
            self::GENDER_NONE => '保密',
        ];
        return self::getDesc($list, $key);
    }


    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function eduRoleTypes($key = null)
    {
        $list = [
            self::EDU_ROLE_STUDENT => '学员',
            self::EDU_ROLE_TEACHER => '讲师',
        ];
        return self::getDesc($list, $key);
    }

}

