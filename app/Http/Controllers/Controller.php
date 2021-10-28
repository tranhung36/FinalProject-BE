<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

<<<<<<< HEAD
    protected function success($data)
    {
        $response = [
            'message' => 'Success',
            'data' => $data,
            'result' => true
        ];
        return response($response, 200);
    }

    protected function error($error)
    {
        $response = [
            'message' => 'Error',
            'data' => [
                'error' => $error
            ],
            'result' => false
        ];
        return response($response, 400);
=======
    protected function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];

        return response()->json($response, $code);
    }

    protected function sendResponseUser($token, $user = [], $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => [
                'user' => $user,
                'access_token' => $token
            ],
            'message' => $message
        ];

        if (!empty($user)) {
            $response['data']['user'] = $user;
        }

        return response()->json($response, $code);
    }

    protected function sendError($error, $errorMessage = [], $code)
    {
        $response = [
            'success' => false,
            'message' => $error
        ];

        if (!empty($errorMessage)) {
            $response['data'] = $errorMessage;
        }

        return response()->json($response, $code);
>>>>>>> sprint1-post-detail
    }
}
