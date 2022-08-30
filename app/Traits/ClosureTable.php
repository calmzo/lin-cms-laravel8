<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait ClosureTable
{
    abstract public static function tableName();

    abstract public static function master(): Builder;

    public static function getDepthColumn(): string
    {
        return 'depth';
    }

    public static function getNodeColumn(): string
    {
        return 'node_id';
    }

    public static function getRootColumn(): string
    {
        return 'root_id';
    }

    public static function getIdColumn(): string
    {
        return 'id';
    }

    public static function getPidColumn(): string
    {
        return 'pid';
    }


    /**
     * 新增节点
     * @param $node_id
     * @param $root_id
     */
    public static function insert($node_id, $root_id)
    {
        $table = self::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();
        $depth_column = static::getDepthColumn();

        // 查询当前节点所有的父节点id 加上当前节点与自身的关系
        $sql = "INSERT INTO {$table} ({$root_column}, {$node_column}, {$depth_column})
            SELECT {$root_column},{$node_id} as {$node_column},{$depth_column} + 1 as {$depth_column} FROM {$table} WHERE {$node_column} = {$root_id}
            UNION ALL
            SELECT {$node_id}, {$node_id}, 0";
        DB::statement($sql);
    }

    /**
     * 删除节点
     * @param $node_id
     */
    public static function remove($node_id)
    {
        $table = static::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();

        // 删除当前节点及所有子节点的关系
        $remove = "DELETE FROM {$table}
            WHERE {$node_column} IN (
                SELECT a FROM (
                    SELECT {$node_column} AS a FROM {$table}
                    WHERE {$root_column} = {$node_id}
                ) as ct
            )";
        DB::statement($remove, ['{$node_column}' => $node_id]);
    }

    /**
     * 解除节点关系
     * @param $node_id
     */
    public static function unbind($node_id)
    {
        $table = static::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();

        // 移除当前节点及节点下所有子节点与当前节点所有父节点的关系
        // 保留子节点与孙子节点的关联关系
        $unbind = "DELETE FROM {$table}
            WHERE {$node_column} IN (
              SELECT d FROM (
                SELECT {$node_column} as d FROM {$table}
                WHERE {$root_column} = {$node_id}
              ) as dct
            )
            AND {$root_column} IN (
              SELECT a FROM (
                SELECT {$root_column} AS a FROM {$table}
                WHERE {$node_column} = {$node_id}
                AND {$root_column} <> {$node_id}
              ) as ct
            )";
        DB::statement($unbind);
    }

    /**
     * 移动节点
     * @param $node_id // 要移动的节点id
     * @param $root_id // 要移动到的父节点id
     */
    public static function move($node_id, $root_id)
    {
        $table = static::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();
        $depth_column = static::getDepthColumn();

        static::unbind($node_id);
        // 通过 CROSS JOIN 计算 $root_id 的所有父节点关系与 $node_id 所有子集关系（包含自身）的笛卡尔积，合并 depth 值并 +1
        $move = "INSERT INTO {$table} ({$root_column}, {$node_column}, {$depth_column})
            SELECT supertbl.{$root_column}, subtbl.{$node_column}, supertbl.{$depth_column}+subtbl.{$depth_column}+1
            FROM {$table} as supertbl
            CROSS JOIN {$table} as subtbl
            WHERE supertbl.{$node_column} = {$root_id}
            AND subtbl.{$root_column} = {$node_id}
        ";
        DB::statement($move);
    }

    /**
     * 获取直接父级节点
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function parent($id)
    {
        $node_column = static::getNodeColumn();
        $depth_column = static::getDepthColumn();
        return static::query()->where($node_column, $id)->where($depth_column, 1)->get();
    }

    /**
     * 获取子集节点数据
     * @param int|array $id 单个节点或多个节点数据
     * @param null $depth 层级深度 null:获取所有子节点  n:获取第n级子节点数据
     * @return \Illuminate\Support\Collection
     */
    public static function children($id, $depth = null)
    {
        $root_column = static::getRootColumn();
        $depth_column = static::getDepthColumn();
        if (is_array($id)) {
            $query = static::query()->whereIn($root_column, $id);
        } else {
            $query = static::query()->where($root_column, $id);
        }

        if ($depth) {
            $query = $query->where($depth_column, $depth);
        } else {
            $query = $query->where($depth_column, '<>', 0);
        }
        return $query->get();
    }

    public static function childrenNodeId($id, $depth = null)
    {
        return static::children($id, $depth)->pluck(static::getNodeColumn());
    }

    /**
     * 获取节点/多个节点的所有父级节点
     * @param int|array $id
     * @return \Illuminate\Support\Collection
     */
    public static function parents($id)
    {
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();
        $depth_column = static::getDepthColumn();
        $query = static::query()->distinct();
        if (is_array($id)) {
            $query = $query->whereIn($node_column, $id);
        } else {
            $query = $query->where($node_column, $id);
        }
        return $query->where($depth_column, '<>', 0)->get();
    }

    /**
     * 所有父级节点ID
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public static function parentsNodeId($id)
    {
        return static::parents($id)->pluck(static::getRootColumn());
    }

    /**
     * 重建数据关系
     * @param int $root_id
     */
    public static function rebuildRelation()
    {
        static::query()->truncate();
        static::buildRelation(0);
    }

    /**
     * 构建数据关系
     * @param $pid
     */
    public static function buildRelation($pid)
    {
        $id_column = self::getIdColumn();
        $pid_column = self::getPidColumn();
        static::master()->where($pid_column, $pid)->chunkById(100, function ($items) use ($id_column, $pid_column) {
            foreach ($items as $item) {
                static::insert($item->$id_column, $item->$pid_column);
                static::buildRelation($item->$id_column);
            }
        }, $id_column);
    }

    /**
     * 构造树形结构
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param int $pid
     * @param $children_field
     * @param $withCount
     * @return mixed
     */
    public static function makeTree($collection, $pid = 0, $children_field = 'children', $withCount = false)
    {
        $pid_column = self::getPidColumn();
        $groups = $collection->groupBy($pid_column);
        $tree = Arr::get($groups, $pid, []);
        foreach ($tree as $index => $node) {
            $tree[$index] = self::makeNode($node, $groups, $children_field, $withCount);
        };
        return $tree;
    }

    /**
     * 递归构造任务树节点
     * @param $node
     * @param $groups
     * @param $children_field
     * @param $withCount
     * @return mixed
     */
    public static function makeNode($node, $groups, $children_field = 'children', $withCount = false)
    {
        $id_column = self::getIdColumn();
        if ($groups->has($node[$id_column])) {
            $children = $groups[$node[$id_column]]->toArray();
            foreach ($children as $index => $item) {
                $children[$index] = static::makeNode($item, $groups, $children_field, $withCount);
            }
            $node[$children_field] = $children;
            if (is_string($withCount)) {
                $node[$withCount] = count($children);
            } elseif (is_bool($withCount) && $withCount == true) {
                $node[$children_field . '_count'] = count($children);
            }
        }
        return $node;
    }
}
