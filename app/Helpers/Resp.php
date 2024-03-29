<?php

namespace App\Helpers;

use stdClass;

final class Resp
{
    private static $response = [
        'status'    =>  false,
        'message'   =>  "",
        'data'      =>  [],
    ];

    public static function success(?string $message,$data = []): void
    {
        self::$response["status"]   = true;
        self::$response["message"]  = $message;
        self::$response["data"]     = $data;

        self::json(self::$response);
    }

    public static function error(?string $message,$data = []): void
    {
        self::$response["message"]  = $message;
        self::$response["data"]     = $data;

        self::json(self::$response);
    }

    public static function json($data = null)
    {
        $data = isset($data) ? $data : self::$response;
        return response()->json($data);
    }

    public static function jsonp($data = null) 
    {
        $data = isset($data) ? $data : self::$response;
        return response()->jsonp($data);
    }

    public static function jsonDataTable($request,$resultData,int $countData): void
    {
        $resp = new stdClass();
        $resp->draw = $request->input('draw');
        $resp->data = $resultData;
        $resp->recordsTotal = $countData;
        $resp->recordsFiltered = $countData;

        self::json($resp);
    }
    
}
