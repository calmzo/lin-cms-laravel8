<?php

namespace App\Repositories;

use App\Models\Upload;

class UploadRepository extends BaseRepository
{
    public function findByIds($ids, $columns = '*')
    {
        return Upload::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }


}
