<?php

namespace App\Lib\Validators;

use App\Caches\MaxUserIdCache;
use App\Caches\UserCache;
use App\Exceptions\BadRequestException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Utils\CodeResponse;

class UserValidator
{

    /**
     * @param int $id
     * @return User
     * @throws BadRequestException
     */
    public function checkUserCache($id)
    {
        $this->checkId($id);

        $userCache = new UserCache();

        $user = $userCache->get($id);

        if (!$user) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.not_found');
        }

        return $user;
    }

    public function checkUser($id)
    {
        $this->checkId($id);
        $userRepo = new UserRepository();

        $user = $userRepo->findById($id);

        if (!$user) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.not_found');
        }

        return $user;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxUserIdCache = new MaxUserIdCache();

        $maxUserId = $maxUserIdCache->get();

        if ($id < 1 || $id > $maxUserId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.not_found');
        }
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('user.name_too_short');
        }

        if ($length > 15) {
            throw new BadRequestException('user.name_too_long');
        }

        return $value;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('user.title_too_long');
        }

        return $value;
    }

    public function checkAbout($about)
    {
        $value = $this->filter->sanitize($about, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('user.about_too_long');
        }

        return $value;
    }

    public function checkGender($value)
    {
        $list = UserModel::genderTypes();

        if (!isset($list[$value])) {
            throw new BadRequestException('user.invalid_gender');
        }

        return $value;
    }

    public function checkArea($area)
    {
        if (empty($area['province'] || empty($area['city']))) {
            throw new BadRequestException('user.invalid_area');
        }

        if (empty($area['county'])) {
            $area['county'] = '***';
        }

        return join('/', $area);
    }

    public function checkAvatar($avatar)
    {
        $value = $this->filter->sanitize($avatar, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('user.invalid_avatar');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkEduRole($value)
    {
        $list = UserModel::eduRoleTypes();

        if (!isset($list[$value])) {
            throw new BadRequestException('user.invalid_edu_role');
        }

        return $value;
    }

    public function checkAdminRole($value)
    {
        if (!$value) return 0;

        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($value);

        if (!$role || $role->deleted == 1) {
            throw new BadRequestException('user.invalid_admin_role');
        }

        return $role->id;
    }

    public function checkVipStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('user.invalid_vip_status');
        }

        return $status;
    }

    public function checkVipExpiryTime($expiryTime)
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_vip_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkLockStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('user.invalid_lock_status');
        }

        return $status;
    }

    public function checkLockExpiryTime($expiryTime)
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_lock_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkIfNameTaken($name)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findByName($name);

        if ($user) {
            throw new BadRequestException('user.name_taken');
        }
    }

}
