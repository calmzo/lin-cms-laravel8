<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{

    public function findById($id)
    {
        return User::query()->find($id);
    }
}
