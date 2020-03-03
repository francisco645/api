<?php

namespace App\Http\Controllers\api;

class ApiError {
    public static function errorMessage($message, $code){
        return [
            'data' => [
                'msg' => $message,
                'code' => $code
            ]
        ];
    }
}