<?php

namespace App\Builders;

use App\Repositories\UserRepository;

class NotificationListBuilder
{

    public function handleUsers(array $notifications)
    {
        $users = $this->getUsers($notifications);

        foreach ($notifications as $key => $notification) {
            $notifications[$key]['sender'] = $users[$notification['sender_id']] ?? new \stdClass();
            $notifications[$key]['receiver'] = $users[$notification['receiver_id']] ?? new \stdClass();
        }

        return $notifications;
    }

    public function getUsers(array $notifications)
    {
        $senderIds = array_column_unique($notifications, 'sender_id');
        $receiverIds = array_column_unique($notifications, 'receiver_id');
        $ids = array_merge($senderIds, $receiverIds);

        $userRepo = new UserRepository();

        $users = $userRepo->findShallowUserByIds($ids);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
//            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
