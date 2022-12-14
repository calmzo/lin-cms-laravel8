<?php

namespace App\Models\Admin;


use app\lib\enum\IdentityTypeEnum;
use app\lib\exception\NotFoundException;
use think\Model;
use think\model\concern\SoftDelete;

class LinUserIdentity extends Model
{
    use SoftDelete;

    public $autoWriteTimestamp = 'datetime';
    protected $hidden = ['create_time', 'update_time', 'delete_time', 'credential'];

    public static function resetPassword(LinUser $currentUser, string $newPassword): void
    {
        $user = self::where('identity_type', IdentityTypeEnum::PASSWORD)
            ->where('identifier', $currentUser->getAttr('username'))
            ->find();

        if (!$user) {
            throw new NotFoundException();
        }

        $user->credential = md5($newPassword);
        $user->save();
    }

    public function checkPassword(string $password): bool
    {
        return $this->getAttr('credential') === md5($password);
    }
}
