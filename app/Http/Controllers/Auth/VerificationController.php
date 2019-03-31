<?php

namespace App\Http\Controllers\Auth;

use App\Facades\UserService;
use App\User;
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
            $message = 'Your verification code has expired. Ask about it once again.';
            return view('auth.verify', compact('message','type'));
        }

        $user = User::find($request->input('id'));
        if (UserService::hasUserVerifiedEmail($user)) {
            $message = 'You have already verified Your email address.';
            return view('auth.verify', compact('message','type'));
        }

        UserService::setEmailAsVerified($user);

        $type = 'success';
        $message = 'E-mail is now verified. You can log in to application.';
        return view('auth.verify', compact('message','type'));
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            abort(
                422,
                'Your e-mail is already verified.'
            );
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(null, 200);
    }
}
