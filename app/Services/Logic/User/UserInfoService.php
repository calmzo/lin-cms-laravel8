<?php

namespace App\Services\Logic\User;

use App\Models\User;
use App\Services\Logic\LogicService;
use App\Traits\UserTrait;

class UserInfoService extends LogicService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUser($id);

        return $this->handleUser($user);
    }

    protected function handleUser(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'area' => $user->area,
            'gender' => $user->gender,
            'vip' => $user->vip,
            'locked' => $user->locked,
            'course_count' => $user->course_count,
            'article_count' => $user->article_count,
            'question_count' => $user->question_count,
            'answer_count' => $user->answer_count,
            'comment_count' => $user->comment_count,
            'vip_expiry_time' => $user->vip_expiry_time,
            'lock_expiry_time' => $user->lock_expiry_time,
            'active_time' => $user->active_time,
            'create_time' => $user->create_time,
            'update_time' => $user->update_time,
        ];
    }

}
