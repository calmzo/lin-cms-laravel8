<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Exceptions\OperationException;
use App\Models\Book;
use App\Utils\CodeResponse;

class BookService
{
    /**
     * @param $bid
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function getBook($bid)
    {
        return Book::query()->find($bid);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getBooks()
    {
        return Book::query()->orderByDesc('create_time')->get();
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public static function createBook($params)
    {
        return Book::query()->create($params);
    }


    /**
     * @param $bid
     * @param $params
     * @return bool|int
     * @throws OperationException
     */
    public static function updateBook($bid, $params)
    {
        try {
            $book = Book::query()->find($bid);
            if (is_null($book)) {
                throw new NotFoundException();
            }
            return $book->update($params);
        } catch (\Exception $e) {
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, '修改图书失败'.$e->getMessage());
        }
    }

    /**
     * @param $bid
     * @return mixed
     */
    public static function deleteBook($bid)
    {
        return Book::query()->where('id', $bid)->delete();
    }
}
