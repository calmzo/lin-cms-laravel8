<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use App\Scopes\LinLogScope;

class LinLog extends BaseModel
{

    protected $fillable = [
        'message', 'user_id', 'username', 'status_code', 'method', 'path', 'permission'
    ];
    public $splitType = 'create_time';

//    /**
//     * 模型的 "booted" 方法
//     */
//    protected static function booted()
//    {
//        static::addGlobalScope(new LinLogScope);
//
//    }


    public static function getLogs(int $start, int $count, $params = []): array
    {
        $logList = self::withSearch(['name', 'start', 'end'], $params);

        $total = $logList->count();
        $logList = $logList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        return [
            'logList' => $logList,
            'total' => $total
        ];
    }

    public static function searchLogs(int $start, int $count, $params = [])
    {
        $logList = self::withSearch(['name', 'start', 'end', 'keyword'], $params);

        $total = $logList->count();
        $logList = $logList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        return [
            'logList' => $logList,
            'total' => $total
        ];
    }


    public static function getUserNames(int $start, int $count)
    {
        $users = self::field('username');

        $total = $users->count();
        $users = $users->limit($start, $count)
            ->group('username')
            ->select();

        return [
            'userList' => $users,
            'total' => $total
        ];
    }

    public function searchNameAttr($query, $value)
    {
        if ($value) {
            $query->where('username', $value);
        }
    }

    public function searchStartAttr($query, $value)
    {
        if ($value) {
            $query->where('create_time', '>= time', $value);
        }
    }

    public function searchEndAttr($query, $value)
    {
        if ($value) {
            $query->where('create_time', '<= time', $value);
        }
    }

    public function searchKeywordAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('message', "%{$value}%");
        }
    }
}
