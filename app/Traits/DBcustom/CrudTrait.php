<?php

namespace App\Traits\DBcustom;

trait CrudTrait 
{
    final public static function getFirst(array $where)
    {
        return self::where($where)->first();
    }

    final public static function getFirstQuery(array $where,$select=false)
    {
        $query = self::query();

        if ($select) {
            if (is_array($select)) {
                $query->select($select);
            }
        }
        
        $query->where($where);
        return $query->first();
    }

    final public static function getFirstOnlyTrashed(array $where)
    {
        return self::onlyTrashed()->where($where)->first();
    }

    final public static function getFirstWithTrashed(array $where)
    {
        return self::withTrashed()->where($where)->first();
    }

    final public static function restoreOnlyTrashed(array $where)
    {
        return self::onlyTrashed()->where($where)->restore();
    }

    final public static function insertId(array $data)
    {
        $insert = self::create($data);
        if ($insert) {
            return $insert->id;
        }
    }

    final public static function updateWhere(array $where, array $data)
    {
        return self::where($where)->update($data);
    }

    final public static function updateWhereOnlyTrashed(array $where, array $data)
    {
        return self::onlyTrashed()->where($where)->update($data);
    }

    final public static function deleteWhere(array $where)
    {
        return self::where($where)->delete();
    }

    final public static function deleteWhereOnlyTrashed(array $where)
    {
        return self::onlyTrashed()->where($where)->forceDelete();
    }
    
}