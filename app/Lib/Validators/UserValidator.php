<?php

namespace App\Lib\Validators;

use App\Caches\MaxUserIdCache;
use App\Caches\UserCache;
use App\Enums\UserEnums;
use App\Exceptions\BadRequestException;
use App\Models\User;
use App\Repositories\RoleRepository;
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

    public function checkGender($value)
    {
        $list = UserEnums::genderTypes();

        if (!isset($list[$value])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_gender');
        }

        return $value;
    }

    public function checkArea($area)
    {
        if (empty($area['province'] || empty($area['city']))) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_area');
        }

        if (empty($area['county'])) {
            $area['county'] = '***';
        }

        return join('/', $area);
    }


    public function checkEduRole($value)
    {
        $list = UserEnums::eduRoleTypes();

        if (!isset($list[$value])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_edu_role');
        }

        return $value;
    }

    public function checkAdminRole($value)
    {
        if (!$value) return 0;

        $roleRepo = new RoleRepository();

        $role = $roleRepo->findById($value);

        if (!$role) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_admin_role');
        }

        return $role->id;
    }

    public function checkVipStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_vip_status');
        }

        return $status;
    }

    public function checkVipExpiryTime($expiryTime)
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_vip_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkLockStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_lock_status');
        }

        return $status;
    }

    public function checkLockExpiryTime($expiryTime)
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.invalid_lock_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkIfNameTaken($name)
    {
        $userRepo = new UserRepository();

        $user = $userRepo->findByName($name);

        if ($user) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user.name_taken');
        }
    }

}
