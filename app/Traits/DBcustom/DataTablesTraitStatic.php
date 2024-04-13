<?php

namespace App\Traits\DBcustom;

use Illuminate\Http\JsonResponse;
use stdClass;

trait DataTablesTraitStatic
{
    private static $varDB;
    private static $req;
    
    final public static function dataTablesQuery($query): void
    {
        self::$varDB = $query;
    }

    final public static function dataTablesRequest($request): void
    { 
        self::$req = $request;    
    }
    
    final public static function dataTablesSelect(array $where): void
    {
        self::$varDB->select($where);
    }
    
    final public static function dataTablesWhere(array $where): void
    {
        self::$varDB->where($where);
    }

    final public static function dataTablesOrderBy(array $columns): void
    {
        $keyOrderBy = $columns[self::$req['order'][0]['column']];
        $valOrderBy = self::$req['order'][0]['dir'];
        self::$varDB->orderBy($keyOrderBy,$valOrderBy);
    }

    final public static function dataTablesSearch(array $searchField): void
    {
        if (self::isAssoc($searchField) == false) {
            $dataSearch = [];
            foreach ($searchField as $val) {
                $row = [
                    $val, 'LIKE','%' . self::$req->input('search')['value'] . '%'
                ];
                $dataSearch[] = $row;
            }
            self::$varDB->where(function($query) use ($dataSearch) {
               foreach ($dataSearch as $val) {
                    $query->orWhere($val[0], $val[1], $val[2]);
               } 
            });
        }
    }

    /**
     * @param array $table = $join = ["penyelia_kategori","penyelia.id_kategori","penyelia_kategori.id"];
     * @param string $instead
     */
    final public static function dataTablesJoin(array $table,string $instead = ""): void 
    {
        if (self::isMultiArray($table)) {
            foreach ($table as $val) {
                if ($instead == "" || $instead == "inner") {
                    self::$varDB->join($val[0],$val[1],"=",$val[2]);
                } else if ($instead == "left") {
                    self::$varDB->leftJoin($val[0],$val[1],"=",$val[2]);
                } else if ($instead == "right") {
                    self::$varDB->rightJoin($val[0],$val[1],"=",$val[2]);
                }
            }
        } else {
            if ($instead == "" || $instead == "inner") {
                self::$varDB->join($table[0],$table[1],"=",$table[2]);
            } else if ($instead == "left") {
                self::$varDB->leftJoin($table[0],$table[1],"=",$table[2]);
            } else if ($instead == "right") {
                self::$varDB->rightJoin($table[0],$table[1],"=",$table[2]);
            }
        }
    }

    final public static function dataTablesWhereIn(array $whereIn): void 
    {
        if (self::isAssoc($whereIn)) {
            if (count($whereIn) > 0) {
                foreach ($whereIn as $key => $value) {
                    self::$varDB->whereIn($key,$value);
                }
            } else {
                self::$varDB->whereIn(array_keys($whereIn),array_values($whereIn));
            }
        }
    }

    final public static function dataTablesGroupBy(array $groupBy): void 
    {
        if (self::isAssoc($groupBy) == false) {
            self::$varDB->groupBy($groupBy);
        }
    }

    final public static function dataTablesOnlyTrashed(): void
    {
        self::$varDB->onlyTrashed();
    }

    final public static function dataTablesWithTrashed(): void
    {
        self::$varDB->withTrashed();
    }

    final public static function dataTablesGet(): object
    {   
        $no = self::$req->input('start');
        self::$varDB->limit(intval(self::$req->input('length')));
        self::$varDB->offset(intval($no));
        $result = self::$varDB->get();
        
        foreach ($result as $val) {
            $no++;
            $val->no = $no;
        }
        return $result;
    }

    final public static function dataTablesRun(): JsonResponse
    {
        $resultData = self::dataTablesGet();
        return self::jsonDataTable($resultData,$resultData->count());
    }

    final public static function dataTablesJson($resultData): JsonResponse
    {
        return self::jsonDataTable($resultData,$resultData->count());
    }

    final private static function jsonDataTable($resultData,int $countData): JsonResponse
    {
        $resp = new stdClass();
        $resp->draw = self::$req->input('draw');
        $resp->data = $resultData;
        $resp->recordsTotal = $countData;
        $resp->recordsFiltered = $countData;

        return response()->json($resp);
    }

    final private static function isAssoc(array $arr): bool
    {
        if (array_keys($arr) !== range(0, count($arr) - 1)) {
            return true;
        } else {
            return false;
        }
    }

    final private static function isMultiArray($a) {
        $rv = array_filter($a,'is_array');
        if(count($rv)>0) return true;
        return false;
    }

}
