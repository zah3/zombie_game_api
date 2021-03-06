<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Constants\Helpers\ExceptionMessage;
use App\Facades\UserService;
use App\Notifications\VerifyEmail;
use App\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be resent if the user did not receive the original email message.
    |
    */
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        $color = 'red';
        if (!$request->hasValidSignature()) {
            $message = VerifyEmail::MESSAGE_DANGER_CODE_EXPIRED;
            return view('auth.verify', compact('message', 'color'));
        }

        $user = User::find($request->input('id'));
        if (UserService::hasUserVerifiedEmail($user)) {
            $message = VerifyEmail::MESSAGE_DANGER_ALREADY_VERIFIED;
            return view('auth.verify', compact('message', 'color'));
        }

        UserService::setEmailAsVerified($user);

        $color = 'green';
        $message = VerifyEmail::MESSAGE_SUCCESS;
        return view('auth.verify', compact('message', 'color'));
    }

    /**
     * POST /verification/resend
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @throws
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
        ]);
        $user = User::whereEmail($request->input('email'))->firstOrFail();

        abort_if(
            UserService::hasUserVerifiedEmail($user) === true,
            422,
            ExceptionMessage::VERIFICATION_USER_IS_ALREADY_VERIFIED
        );
        UserService::sendEmailVerificationNotification($user);

        return response()->json(null, 200);
    }
}
