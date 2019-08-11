<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequestNotification;
use App\Notifications\PasswordChangeNotification;
use App\Entities\PasswordReset;
use App\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $user = User::whereEmail($request->email)->withEmailVerifiedAt()->first();

        abort_if(
            $user === null,
            404,
            User::MESSAGE_USER_NOT_VERIFIED
        );
        
        // It means that - user probably haven't receive email with secret code
        $passwordResetForUser = $user->passwordReset;

        if ($passwordResetForUser) {
            $user->passwordReset()->delete();
        }

        $token = Utilities::generateRandomUniqueString();
        PasswordReset::create(
            [
                'user_id' => $user->id,
                'token' => Hash::make($token),
            ]
        );
        $user->notify(
            new PasswordResetRequestNotification($token)
        );
        return response()->json([
            'message' => PasswordChangeNotification::MESSAGE_SUCCESS,
            201
        ]);
    }
}
