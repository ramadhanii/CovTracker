<?php 

namespace App\Http\Helper;

use Exception;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;

class Response{
    public static function success($data = null, $mess = "Success", $code = 200){
        $res = [
            "status"    => true,
            "code"      => $code,
            "message"   => $mess,
            "data"      => $data
        ];

        return self::raw($res);
    }

    public static function error($data = null, $mess = "Internal System Error", $code = 200){
        $res = [
            "status"    => false,
            "code"      => $code,
            "message"   => $mess,
            "data"      => $data
        ];

        return self::raw($res);
    }

    public static function raw($data){

        Log::info(SESSIONID . "\tRESPONSE\t\t\t" . is_array($data)? json_encode($data) : $data);
        return new HttpResponse($data);
    }
}