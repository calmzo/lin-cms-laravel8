<?php

namespace App\Services\Logic\Vip;

use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use Illuminate\Pagination\LengthAwarePaginator;

class UserListService extends LogicService
{

    public function handle()
    {

        $params = $this->request->all();
        $params['vip'] = 1;
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 15;
        $sort = $params['sort'] ?? '';

        $userRepo = new UserRepository();

        $pager = $userRepo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    /**
     * @param $pager LengthAwarePaginator
     * @return mixed
     */
    protected function handleUsers($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $users = collect($paginate->items())->toArray();

//        $baseUrl = kg_cos_url();

        $items = [];

        foreach ($users as $user) {

//            $user['avatar'] = $baseUrl . $user['avatar'];

            $items[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'title' => $user['title'],
                'about' => $user['about'],
                'vip' => $user['vip'],
            ];
        }
        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
