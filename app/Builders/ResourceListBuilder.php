<?php

namespace App\Builders;

use App\Repositories\UploadRepository;

class ResourceListBuilder extends BaseBuilder
{

    public function handleUploads($relations)
    {
        $uploads = $this->getUploads($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['upload'] = $uploads[$value['upload_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getUploads($relations)
    {
        $ids = array_column_unique($relations, 'upload_id');

        $uploadRepo = new UploadRepository();

        $columns = ['id', 'name', 'path', 'mime', 'md5', 'size'];

        $uploads = $uploadRepo->findByIds($ids, $columns);
        $result = $uploads->pluck('id')->toArray();
//        $result = [];
//
//        foreach ($uploads->toArray() as $upload) {
//
//            $id = $this->crypt->encryptBase64($upload['id'], null, true);
//
//            $upload['url'] = $this->url->get(['for' => 'home.download', 'id' => $id]);
//
//            $result[$upload['id']] = $upload;
//        }

        return $result;
    }

}
