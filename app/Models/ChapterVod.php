<?php

namespace App\Models;

class ChapterVod extends BaseModel
{
    public $fillable = [];

    public function setFileTranscodeAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['file_transcode'] = $value;
    }

    public function getFileTranscodeAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }

    public function setFileRemoteAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['file_remote'] = $value;
    }

    public function getFileRemoteAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }
}
