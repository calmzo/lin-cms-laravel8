<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 按照日期分表的作用域
 */
class LinLogScope implements Scope
{


    public function apply(Builder $builder, Model $model)
    {
        //当前链接
        $connection = $model->getConnectionName() ?: Config('database.default');
        // 获取设置在 model 中的日期字段
        $splitType = isset($model->splitType) ? $model->splitType : '';

        //当前表
        $modelTable = $model->getTable();

        $wheres = $builder->getQuery()->wheres;
        // 从 where 中获取 日期 条件
        foreach ($wheres as $where) {
            if (isset($where['column']) && $where['column'] === $splitType) {
                $splitValue = $where['values'];
                break;
            }
        }
        //传时间根据时间分表查询
        if (isset($splitValue)) {
            $startDate = Carbon::parse($splitValue[0]);
            $endDate = Carbon::parse($splitValue[1]);
            $queries = collect();

            for ($i = $startDate; $i->format('Ym') <= $endDate->format('Ym'); $i->addMonth()) {
                $table = $modelTable . '_' . $i->format('Ym');
                $db = DB::connection($connection)->table($table);

                // 拼接 where 条件, 目前处理了 between, in, = 三种条件
                foreach ($wheres as $where) {

                    if ($where['type'] == 'between') {
                        $db->whereBetween($where['column'], $where['values'], $where['boolean'], $where['not']);
                    } else if ($where['type'] == 'In') {
                        $db->whereIn($where['column'], $where['values'], $where['boolean']);
                    } else if ($where['type'] == 'NotIn') {
                        $db->whereNotIn($where['column'], $where['values'], $where['boolean']);
                    } else if ($where['type'] == 'Basic') {
                        $db->where($where['column'], $where['operator'], $where['value'], $where['boolean']);
                    } else if ($where['type'] == 'Nested') {
                        $db->addNestedWhereQuery($where['query'], $where['boolean']);
                    } else if ($where['type'] == 'Exists') {
                        $exists = $where['query'];
                        $db->whereExists(function ($query) use ($exists, $modelTable, $table) {
                            $wheres = $exists->wheres;
                            $column = $wheres[0];
                            // 替换表为对应带日期后缀的表
                            $column['first'] = str_replace($modelTable, $table, $column['first']);
                            $query->select(DB::raw(1))
                                ->from($exists->from)
                                ->whereRaw("{$column['first']} {$column['operator']} {$column['second']}");
                            // 添加额外的补充条件, 目前只加一条
                            if (isset($wheres[1])) {
                                $query->where($wheres[1]['column'], $wheres[1]['operator'], $wheres[1]['value'], $wheres[1]['boolean']);
                            }
                        });
                    }
                }
                $queries[] = $db;
            }
        } else {
            //不传时间查询所有相关表
            //当前库
            $databases = Config('database.connections.' . $connection . '.database');
            //获取全部表
            $tables = DB::select('show tables');
            $tables = array_column($tables, 'Tables_in_' . $databases);
            //获取当前表前缀的所有表
            $tables = collect($tables)->filter(function ($value, $key) use ($modelTable) {
                return strstr($value, $modelTable);
            })->all();

            $queries = collect();
            //查询所有表
            foreach ($tables as $table) {
                $db = DB::connection($connection)->table($table);
                // 拼接 where 条件, 目前处理了 between, in, = 三种条件
                foreach ($wheres as $where) {

                    if ($where['type'] == 'between') {
                        $db->whereBetween($where['column'], $where['values'], $where['boolean'], $where['not']);
                    } else if ($where['type'] == 'In') {
                        $db->whereIn($where['column'], $where['values'], $where['boolean']);
                    } else if ($where['type'] == 'NotIn') {
                        $db->whereNotIn($where['column'], $where['values'], $where['boolean']);
                    } else if ($where['type'] == 'Basic') {
                        $db->where($where['column'], $where['operator'], $where['value'], $where['boolean']);
                    } else if ($where['type'] == 'Nested') {
                        $db->addNestedWhereQuery($where['query'], $where['boolean']);
                    } else if ($where['type'] == 'Exists') {
                        $exists = $where['query'];
                        $db->whereExists(function ($query) use ($exists, $modelTable, $table) {
                            $wheres = $exists->wheres;
                            $column = $wheres[0];
                            // 替换表为对应带日期后缀的表
                            $column['first'] = str_replace($modelTable, $table, $column['first']);
                            $query->select(DB::raw(1))
                                ->from($exists->from)
                                ->whereRaw("{$column['first']} {$column['operator']} {$column['second']}");
                            // 添加额外的补充条件, 目前只加一条
                            if (isset($wheres[1])) {
                                $query->where($wheres[1]['column'], $wheres[1]['operator'], $wheres[1]['value'], $wheres[1]['boolean']);
                            }
                        });
                    }
                }
                $queries[] = $db;
            }
        }
        $unionQuery = $queries->shift();
        $queries->each(function ($item, $key) use ($unionQuery) {
            $unionQuery->unionAll($item);
        });
        $sql = $builder->from(DB::raw("({$unionQuery->toSql()}) as {$modelTable}"))
            ->mergeBindings($unionQuery);
        return $sql;
    }

}
