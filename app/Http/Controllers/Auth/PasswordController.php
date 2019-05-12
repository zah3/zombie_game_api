<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequestNotification;
use App\Notifications\PasswordResetSuccessNotification;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;

class PasswordController extends Controller
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
     * POST password
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
        $userWithSameEmailInPasswordResetTable = PasswordReset::whereEmail($request->email)->first();
        // It means that - user probably haven't receive email with secret code
        if ($userWithSameEmailInPasswordResetTable) {
            $userWithSameEmailInPasswordResetTable->delete();
        }
        $user = User::withEmail($request->email)->first();

        $passwordReset = PasswordReset::create(
            [
                'email' => $user->email,
                'token' => Utilities::generateRandomUniqueString(),
            ]
        );
        $user->notify(
            new PasswordResetRequestNotification($passwordReset->token)
        );
        return response()->json([
            'message' => PasswordResetSuccessNotification::MESSAGE_SUCCESS,
        ]);
    }
}
