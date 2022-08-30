<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Exceptions\OperationException;
use App\Models\Organization;
use App\Utils\CodeResponse;

class OrganizationService
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function getOrganization($id)
    {
        return Organization::query()->find($id);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getOrganizations()
    {
        return Organization::query()->get();
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public static function createOrganization($params)
    {
        return Organization::query()->create($params);
    }


    /**
     * @param $id
     * @param $params
     * @return bool|int
     * @throws OperationException
     */
    public static function updateOrganization($id, $params)
    {
        try {
            $book = Organization::query()->find($id);
            if (is_null($book)) {
                throw new NotFoundException();
            }
            return $book->update($params);
        } catch (\Exception $e) {
            throw new OperationException(CodeResponse::OPERATION_EXCEPTION, '修改部门失败'.$e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function deleteOrganization($id)
    {
        return Organization::query()->where('id', $id)->delete();
    }
}
