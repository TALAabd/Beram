<?php

namespace App\Traits;

trait ApiResponser
{
    public static  function successResponse($data = null, $message = null, $code = 200, $token = null)
    {
        return response()->json([
            'status'  => 'Success',
            'message' =>  __('messages.'.$message),
            'list'    => $data,
            'returnedCode' => $code
        ], $code, $token ? ['Authorization' => $token] : []);
    }

    public static function errorResponse($data = null, $message = null, $code = 500)
    {
        return response()->json([
            'status'       => 'Error',
            'message'      => __('messages.'.$message),
            'list'         => $data,
            'returnedCode' => $code
        ], $code);
    }
}
