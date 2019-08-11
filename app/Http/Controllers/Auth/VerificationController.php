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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        $type = 'danger';
        if (!$request->hasValidSignature()) {
            $message = VerifyEmail::MESSAGE_DANGER_CODE_EXPIRED;
            return view('auth.verify', compact('message', 'type'));
        }

        $user = User::find($request->input('id'));
        if (UserService::hasUserVerifiedEmail($user)) {
            $message = VerifyEmail::MESSAGE_DANGER_ALREADY_VERIFIED;
            return view('auth.verify', compact('message', 'type'));
        }

        UserService::setEmailAsVerified($user);

        $type = 'success';
        $message = VerifyEmail::MESSAGE_SUCCESS;
        return view('auth.verify', compact('message', 'type'));
    }

    /**
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
        $user = User::whereEmail($request->input('email'))->first();
        abort_if(
            UserService::hasUserVerifiedEmail($user) === true,
            422,
            ExceptionMessage::VERIFICATION_USER_IS_ALREADY_VERIFIED
        );
        UserService::sendEmailVerificationNotification($user);

        return response()->json(null, 200);
    }
}
