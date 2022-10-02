<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository extends BaseRepository
{
    public function findById($id)
    {
        return Account::query()->find($id);
    }
}
