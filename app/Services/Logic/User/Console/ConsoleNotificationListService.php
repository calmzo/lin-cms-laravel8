<?php

namespace App\Services\Logic\User\Console;

use App\Builders\NotificationListBuilder;
use App\Repositories\NotificationRepository;
use \App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleNotificationListService extends LogicService
{

    public function handle($params)
    {
        $uid = AccountLoginTokenService::userId();
        $params['receiver_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;
        $notifyRepo = new NotificationRepository();

        $pager = $notifyRepo->paginate($params, $sort, $page, $limit);

        return $this->handleNotifications($pager);
    }

    protected function handleNotifications($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $notifications = collect($paginate->items())->toArray();
        $builder = new NotificationListBuilder();

        $users = $builder->getUsers($notifications);

        $items = [];

        foreach ($notifications as &$value) {

            $sender = $users[$value['sender_id']] ?? new \stdClass();
            $receiver = $users[$value['receiver_id']] ?? new \stdClass();

            $items[] = [
                'id' => $value['id'],
                'viewed' => $value['viewed'],
                'event_id' => $value['event_id'],
                'event_type' => $value['event_type'],
                'event_info' => $value['event_info'],
                'create_time' => $value['create_time'],
                'sender' => $sender,
                'receiver' => $receiver,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
