<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper\ResponseData;

class LoginController extends Controller
{
    /**
     * Login
     */

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return $this->sendResponseUser($token, auth()->user(), 'Login successfully.');
        } else {
            return $this->sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Logout
     */

    public function logout()
    {
        if (Auth::check()) {
            $logout = Auth::user()->token()->revoke();
            return $this->sendResponse($logout, 'Logout successfully.');
        } else {
            return $this->sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }
}
