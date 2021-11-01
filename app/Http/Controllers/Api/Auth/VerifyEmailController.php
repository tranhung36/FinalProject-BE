<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendResponse('Success.', 'Already Verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->sendResponse('Success.', 'verification-link-sent');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendResponse('Success.', 'Email already verified.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->sendResponse('Success.', 'Email has been verified.');
    }

    public function resend()
    {
        return $this->sendError('Error.', 'Your email address has not been verified.', 401);
    }
}
