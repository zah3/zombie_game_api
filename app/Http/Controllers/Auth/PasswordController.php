<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Creates reset password notification
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::withEmail($request->email)->first();
        abort_if(
            $user === null,
            404,
            PasswordResetSuccess::MESSAGE_ERROR_CANNOT_FIND_EMAIL
        );
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
            ]
        );
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json([
            'message' => 'We have e - mailed your password reset link!'
        ]);
    }

    /**
     * Finds token
     *
     * @param $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        abort_if(
            $passwordReset === null,
            404,
            PasswordResetSuccess::MESSAGE_ERROR_INVALID_TOKEN
        );

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            abort(
                404,
                PasswordResetSuccess::MESSAGE_ERROR_INVALID_TOKEN
            );
            return $passwordReset;
        }
    }
}
