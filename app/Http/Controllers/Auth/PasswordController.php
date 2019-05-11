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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('store');
    }
    /**
     * POST api/password
     * Creates reset password notification
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::withEmail($request->email)->first();

        $passwordReset = PasswordReset::create(
            [
                'email' => $user->email,
                'token' => str_random(60)
            ]
        );
        $user->notify(
            new PasswordResetRequest($passwordReset->token)
        );
        return response()->json([
            'message' => PasswordResetSuccess::MESSAGE_SUCCESS,
        ]);
    }

    /**
     * GET api/password/token
     * Finds token
     *
     * @param $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($token)
    {
        $passwordReset = PasswordReset::whereToken($token)->firstOrFail();

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            abort(
                404,
                PasswordResetSuccess::MESSAGE_ERROR_INVALID_TOKEN
            );
        }
        return $passwordReset;
    }
}
