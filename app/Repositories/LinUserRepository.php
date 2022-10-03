<?php

namespace App\Repositories;

use App\Models\Admin\LinUser;

class LinUserRepository extends BaseRepository
{
    public function findById($id)
    {
        return LinUser::query()->find($id);
    }
}
