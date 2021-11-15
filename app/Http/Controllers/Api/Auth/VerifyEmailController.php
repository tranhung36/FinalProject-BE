<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function reSendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendResponse('Success.', 'Already Verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->sendResponse('Success.', 'Verification link sent!');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return $this->sendError('Error', 'Email already verified', 403);
        }

        if (!hash_equals(
            (string) $request->hash,
            sha1($user->getEmailForVerification())
        )) {
            return $this->sendError('Error', 'Unauthorized', 401);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->sendResponse('Success', 'Email has been verified');
    }
}
