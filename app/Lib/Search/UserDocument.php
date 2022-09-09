<?php

namespace App\Lib\Search;

use App\Models\User;

class UserDocument
{

    /**
     * 设置文档
     *
     * @param User $user
     * @return \XSDocument
     */
    public function setDocument(User $user)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($user);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param User $user
     * @return array
     */
    public function formatDocument(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'title' => $user->title,
            'avatar' => $user->avatar,
            'about' => $user->about,
            'gender' => $user->gender,
            'area' => $user->area,
            'vip' => $user->vip,
        ];
    }

}
