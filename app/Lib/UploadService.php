<?php

namespace App\Lib;

use App\Models\Admin\LinFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadService extends File
{
    public static function upload($files)
    {
        $ret = [];
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                $md5 = self::generateMd5($file);
                $exists = LinFile::query()->where('md5', $md5)->first();
                if ($exists) {
                    $path = $exists['path'];
                    /*
                     * 获取给定文件的 URL
                     * 访问URL
                     */
                    $url = asset(Storage::url($path));
                    array_push($ret, [
                        'id' => $exists['id'],
                        'key' => $key,
                        'path' => $url,
                        'url' => $url
                    ]);
                } else {
                    $name = $file->getClientOriginalName();
                    $size = $file->getSize();
                    $extension = $file->getExtension();
                    //上传保存文件
                    $path = Storage::disk('public')->put('images', $file);
                    $linFile = LinFile::query()->create([
                        'name' => $name,
                        'path' => $path,
                        'size' => $size,
                        'extension' => $extension,
                        'md5' => $md5,
                        'type' => 1
                    ]);
                    /*
                     * 获取给定文件的 URL
                     * 访问URL
                     */
                    $url = asset(Storage::url($path));
                    array_push($ret, [
                        'id' => $linFile->id,
                        'key' => $key,
                        'path' => $url,
                        'url' => $url
                    ]);

                }
            }
        } else {
            $file = $files;
            $md5 = self::generateMd5($file);
            $exists = LinFile::query()->where('md5', $md5)->first();
            if ($exists) {
                $path = $exists['path'];
                /*
                 * 获取给定文件的 URL
                 * 访问URL
                 */
                $url = asset(Storage::url($path));
                array_push($ret, [
                    'id' => $exists['id'],
                    'key' => 0,
                    'path' =>$url,
                    'url' => $url
                ]);
            } else {
                $name = $file->getClientOriginalName();
                $size = $file->getSize();
                $extension = $file->getExtension();
                //上传保存文件
                $path = Storage::disk('public')->put('images', $file);
                $linFile = LinFile::query()->create([
                    'name' => $name,
                    'path' => $path,
                    'size' => $size,
                    'extension' => $extension,
                    'md5' => $md5,
                    'type' => 1
                ]);
                /*
                 * 获取给定文件的 URL
                 * 访问URL
                 */
                $url = asset(Storage::url($path));
                array_push($ret, [
                    'id' => $linFile->id,
                    'key' => 0,
                    'path' => $url,
                    'url' => $url
                ]);

            }
        }
        return $ret;


    }

    private function generateMd5($file)
    {
        return md5($file->getClientOriginalName());
    }

}
