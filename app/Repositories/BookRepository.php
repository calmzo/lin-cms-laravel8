<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends BaseRepository
{
    public function findById($id)
    {
        return Book::query()->find($id);
    }

    public function findAll($where = [], $sort = 'latest')
    {
        return Book::query()->where($where)->latest()->get();
    }
}
