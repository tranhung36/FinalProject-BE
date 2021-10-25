<?php

namespace App\Helper;

class ResponseData
{
    public static function dataResponseSuccess($token, $user)
    {
        return response()->json([
            'message' => 'Successfully',
            'data' => [
                'user' => [
                    $user
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            'result' => true
        ], 200);
    }

    public static function dataResponseFail()
    {
        return response()->json([
            'message' => 'Invalid data',
            'result' => false
        ], 401);
    }
}
