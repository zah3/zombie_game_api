<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequestNotification;
use App\Notifications\PasswordResetSuccessNotification;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:5,1')->only('store');
    }

    /**
     * POST api/password
     * Creates reset password notification
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::whereEmail($request->email)->first();
        // It means that - user probably haven't receive email with secret code
        $userInPasswordResetTable = PasswordReset::whereUserId($user->id)->first();
        if ($userInPasswordResetTable) {
            $userInPasswordResetTable->delete();
        }

        $passwordReset = PasswordReset::create(
            [
                'user_id' => $user->id,
                'token' => Utilities::generateRandomUniqueString(),
            ]
        );
        $user->notify(
            new PasswordResetRequestNotification($passwordReset->token)
        );
        return response()->json([
            'message' => PasswordResetSuccessNotification::MESSAGE_SUCCESS,
            201
        ]);
    }
}
